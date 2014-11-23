<?php
/*
 * php file which contains all functionality needed for the Admin user
 *
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

    maxDate.setMonth(maxDate.getMonth() + 3); //Limit of 3 months in the future

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
            url: '<?php echo base_url(); ?>index.php/shift/getCalendar',
            error: function(textStatus, errorThrown) {
                alert(errorThrown.responseText);
                alert(textStatus);
            }
        },
        fixedWeekCount: false,
        loading: function(bool) {
            $('#loading').toggle(bool);
        },
        viewRender: function(view){
            transitionPopup($("#missing-shift"), false);
            transitionPopup($("#warning"), false);
            transitionPopup($("#warning-future"), false);
            countStaffShifts(view);
        },eventAfterAllRender: function(view){
            highlighUnderstaffed(view);
        },
        eventClick: function(calEvent, jsEvent, view) {
            if (calEvent.onShift == 1){
                //Only process deletion if the user is on this shift

                deleteMessage = "Are you sure you want to remove " + calEvent.shiftUserName+" from date " + calEvent.shiftDate + "?";
                if(confirm (deleteMessage)){
                    $.ajax({
                        url: "<?php echo base_url(); ?>index.php/shift/removeShift",
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

            dayClicked = date;
            //Ensure that the modal display doesn't have incorrect (old) checked boxes
            if(newShifts.length > 0){
                newShifts.forEach(function(tickbox){
                    $('#newShift' + tickbox).prop('checked', false);
                });
                newShifts.length = 0;
            }

            //Return all events for the clicked day
            dayEvents = $('#calendar').fullCalendar( 'clientEvents' ,function(event){
                return moment(event.start).isSame(date, 'day');
            });
            dayEvents.forEach(function(event){
                //Populate the modal checkboxes, if a user is already working th selected day
                $('#newShift' + event.userID).prop('checked', true);
                newShifts.push(event.userID); //Keep a list of which checkboxes have been ticked
                });

            //Display the modal popup.
            $('#userSelection').modal('toggle');
            $('#userSelection').modal('show');

        }
    });

    $("#warning-close").click(function(){
        transitionPopup($("#warning"), false);
    });

    $("#warning-future-close").click(function(){
        transitionPopup($("#warning-future"), false);
    });

    $("#instructionsHeader").click(function(){
        displayInstructions = !displayInstructions;
        transitionPopup($("#instructionsBody"), displayInstructions);
    });

    $("#shiftBodyHeader").click(function(){
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
        $.ajax({
            url: "<?php echo base_url(); ?>index.php/shift/remove_shiftUser",
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
    startDate = (view.start.year()) + '-' + (view.start.month()+1) + '-' + (view.start.date());
    endDate = (view.end.year()) + '-' + (view.end.month()+1) + '-' + (view.end.date());
    $("#shiftCountBody").html('');
    if(view.name == 'basicWeek'){
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
    underStaffed.forEach(function(cell){
        $('.fc-day[data-date="' + cell + '"]').removeClass('activeDay');
    });
    monthEvents = $('#calendar').fullCalendar( 'clientEvents' ,function(event){
        //Get all events for the current month
        return moment(event.start).isSame($('#calendar').fullCalendar('getDate'), 'month');
    });

    nextDay = moment(view.start); //Clone the moment so that we don't affect the original

    while(moment(nextDay).isBefore(moment(view.end))) {

        dayEventsCalculate = $('#calendar').fullCalendar('clientEvents', function (event) {
            return moment(event.start).isSame(nextDay, 'day');
        });

        dayShiftMissingCounter = 0;
        dayEventsCalculate.forEach(function (event) {
            //Count up all of the shifts the user is working for the clicked week
            if (event.onShift == 1) {
                dayShiftMissingCounter += 1;
            }
        });
        if (dayShiftMissingCounter < 5){
            console.log("missing shift");
            var day,
                month;
            if (nextDay.date() < 10){
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
            console.log(date);
            $('.fc-day[data-date="' + date + '"]').addClass('activeDay');
            underStaffed.push(date);
        }
        nextDay = nextDay.add(1, 'days');
    }
}
</script>
