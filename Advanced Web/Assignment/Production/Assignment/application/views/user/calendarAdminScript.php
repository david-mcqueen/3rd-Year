<?php
/**
 * David McQueen
 * 10153465
 * December 2014
 */

/**
 * Calendar controls, specific to Admin users
 */
?>
<link href='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>application/views/css/user/calendar.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/fullcalendar.print.css' rel='stylesheet' media='print' />

<script src='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/lib/jquery.min.js'></script>
<script src='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/lib/moment.min.js'></script>
<script src='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/fullcalendar.js'></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script>

var dayClicked,
    maxDate = new Date(),
    deleteMessage,
    dayEvents,
    singleDayEvents,
    singleDayShiftCount,
    onShift,
    isAdmin = <?php echo $isAdmin;?>,
    newShifts = [],
    displayInstructions = false,
    displayShiftCount = false,
    startDate,
    endDate,
    monthEvents,
    nextDay,
    dayEventsCalculate,
    dayShiftMissingCounter,
    date,
    underStaffed = [];

$(document).ready(function() {

    $('#calendar').fullCalendar({
        header: { //Define the buttons
            left: 'prev,next today',
            center: 'title',
            right: 'month, basicWeek'
        },
        firstDay:1, //Week starts on Monday
        defaultDate: Date(),
        weekNumbers: true,
        editable: true, //So users can click to add shifts
        eventLimit: true, // allow "more" link when too many events
        eventStartEditable: false, //The event can not be dragged to a new day
        eventDurationEditable: false, //Cant drag the event duration over multiple days
        events: {
            //The location in which the event information is stored
            url: '<?php echo base_url(); ?>index.php/shift/getCalendar',
            error: function(textStatus, errorThrown) {
                alert(errorThrown.responseText);
                alert(textStatus);
            }
        },
        fixedWeekCount: false,
        loading: function(bool) {
            //Display a loading message
            $('#loading').toggle(bool);
        },
        viewRender: function(view){
            //Moving to a new view, so remove all error messages
            transitionPopup($("#missing-shift"), false);
            transitionPopup($("#warning"), false);
            transitionPopup($("#warning-future"), false);

            //Count shifts each user is working
            countStaffShifts(view);
        },
        eventAfterAllRender: function(view){
            //After all events are on the calendar, determine which days are understaffed
            highlighUnderstaffed(view);
        },
        eventClick: function(calEvent, jsEvent, view) {
            if (calEvent.onShift == 1){
                //Only process deletion if the user is on this shift

                //Confirm the user wants to delete the shift
                deleteMessage = "Are you sure you want to remove " + calEvent.shiftUserName+" from date " + calEvent.shiftDate + "?";
                if(confirm (deleteMessage)){
                    $.ajax({
                        url: "<?php echo base_url(); ?>index.php/shift/removeShift_shiftID",
                        dataType: 'json',
                        data: {
                            id: calEvent.id
                        },
                        success: function (result) {
                            if (!result[0].success){
                                transitionPopup($("#warning"), true);
                            }else{
                                $('#calendar').fullCalendar('refetchEvents');
                                transitionPopup($("#warning"), false);
                                transitionPopup($("#warning-future"), false);
                            }
                        },
                        error: function () {
                            alert("Oops! Something went wrong");
                        }
                    });
                }
            }
        },
        dayClick: function(date, jsEvent, view) {
            //Display a modal with all staff on.

            dayClicked = date;

            //Ensure that the modal display doesn't have incorrect (old) checked boxes
            if(newShifts.length > 0){
                newShifts.forEach(function(tickbox){
                    $('#newShift' + tickbox).prop('checked', false);
                });
                newShifts.length = 0;
            }

            //Return all calendar events for the clicked day
            dayEvents = $('#calendar').fullCalendar( 'clientEvents' ,function(event){
                return moment(event.start).isSame(date, 'day');
            });

            dayEvents.forEach(function(event){
                //Populate the modal checkboxes, if a user is already working the selected day
                $('#newShift' + event.userID).prop('checked', true);
                newShifts.push(event.userID); //Keep a list of which checkboxes have been ticked
                });

            //Display the modal popup.
            $('#userSelection').modal('toggle');
            $('#userSelection').modal('show');
        }
    });

    $("#warning-close").click(function(){
        //Close the warning popup
        transitionPopup($("#warning"), false);
    });

    $("#warning-future-close").click(function(){
        //Close the warning (3 months in future) popup
        transitionPopup($("#warning-future"), false);
    });

    $("#instructionsHeader").click(function(){
        //Toggle the instructions display
        displayInstructions = !displayInstructions;
        transitionPopup($("#instructionsBody"), displayInstructions);
    });

    $("#shiftBodyHeader").click(function(){
        //Toggle the shift count display
        displayShiftCount = !displayShiftCount;
        transitionPopup($("#shiftCountBody"), displayShiftCount);
    });

    //Populate the instructions for the user
    $('#instructionsBody').html('<li>Click on a users shift to remove that shift</li>' +
    '<li>Click on a day to add / remove shifts in bulk (for the specified day)</li>' +
    '<li>To view the amount of shifts for each staff, enter week view</li>' +
    '<li>Understaffed days are highlighted</li>');

    $('#shiftCountContainer').removeClass('hidden');
});


function modifyShift(userID, element){
    //Add or remove a shift depending on if the check box is ticked or unticked.
    //Called from $('#userSelection')

    if(element.checked){
        console.log('AddingShift');
        $.ajax({
            url: "<?php echo base_url(); ?>index.php/shift/addShift",
            dataType: 'json',
            data: {
                userID: userID,
                start: dayClicked.format()
            },
            success: function (result) {
                newShifts.push(userID);
                $('#calendar').fullCalendar('refetchEvents');
                countStaffShifts($('#calendar').fullCalendar('getView'));
                highlighUnderstaffed($('#calendar').fullCalendar('getView'));
            },
            error: function () {
                alert("Oops! Something went wrong.");
            }
        });
    }else{
        //Remove the user from the shift
        $.ajax({
            url: "<?php echo base_url(); ?>index.php/shift/removeShift_userID",
            dataType: 'json',
            data: {
                userID: userID,
                shiftDate: dayClicked.format()
            },
            success: function (result) {
                if (!result[0].success){
                    transitionPopup($("#warning"), true);
                }else{
                    $('#calendar').fullCalendar('refetchEvents');
                    transitionPopup($("#warning"), false);
                    transitionPopup($("#warning-future"), false);
                    countStaffShifts($('#calendar').fullCalendar('getView'));
                    highlighUnderstaffed($('#calendar').fullCalendar('getView'));
                }
            },
            error: function () {
                alert("Oops! Something went wrong");
            }
        });
    }

};

function transitionPopup(element, display){
    //Apply / Remove the transition classes to the provided element
    if(display){
        element.removeClass('hidden');
        setTimeout(function(){
            element.addClass('in');
            element.removeClass('out');
        },300);
    }else{
        element.addClass('out');
        element.removeClass('in');
        setTimeout(function(){
            element.addClass('hidden');
        },300);
    }
};

function countStaffShifts(view){
    //Construct the Date values
    startDate = (view.start.year()) + '-' + (view.start.month()+1) + '-' + (view.start.date());
    endDate = (view.end.year()) + '-' + (view.end.month()+1) + '-' + (view.end.date());

    $("#shiftCountBody").html('');
    if(view.name == 'basicWeek'){
        //If in week view, display staff shift count
        $.ajax({
            url: "<?php echo base_url(); ?>index.php/user/countUsersShifts",
            dataType: 'json',
            data: {
                start: startDate,
                finish: endDate
            },
            success: function (result) {
                console.log('success');
                result.forEach(function(user){
                    $("#shiftCountBody").html($("#shiftCountBody").html() + user['forename'] + ": " + (-1 * (5 - user['shiftsCount'])) + "</br>");
                });

                transitionPopup($("#shiftCountBody"), true);
            },
            error: function () {
                console.log('failed');
            }
        });
    }else if(view.name == 'month'){
        $("#shiftCountBody").html('Select a week to view staff shift counter');
    }
}

function highlighUnderstaffed(view){

    //Remove previously highlighted days
    underStaffed.forEach(function(cell){
        $('.fc-day[data-date="' + cell + '"]').removeClass('activeDay');
    });

    nextDay = moment(view.start); //Clone the moment so that we don't affect the original

    while(moment(nextDay).isBefore(moment(view.end))) {
        //Loop through each day in the view

        //Get all events for the day
        dayEventsCalculate = $('#calendar').fullCalendar('clientEvents', function (event) {
            return moment(event.start).isSame(nextDay, 'day');
        });

        dayShiftMissingCounter = 0;
        dayEventsCalculate.forEach(function (event) {
            //Count up all of the shifts the user is working for the week
            if (event.onShift == 1) {
                dayShiftMissingCounter += 1;
            }
        });
        if (dayShiftMissingCounter < 5){
            var day,
                month;
            if (nextDay.date() < 10){ //If the first 9 days of the month, append 0 - "03" instead of "3"
                day = "0" + nextDay.date();
            }else {
                day = nextDay.date();
            }
            if ((nextDay.month()+1) < 10){
                month = "0" + (nextDay.month()+1);
            }else {
                month = (nextDay.month()+1);
            }

            date = (nextDay.year()) + '-' + month + '-' + day;
            $('.fc-day[data-date="' + date + '"]').addClass('activeDay');
            underStaffed.push(date);
        }
        nextDay = nextDay.add(1, 'days');
    }
}
</script>
