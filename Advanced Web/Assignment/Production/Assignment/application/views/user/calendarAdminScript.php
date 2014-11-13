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
    onShift,
    isAdmin = <?php echo $isAdmin;?>,
    newShifts = [];

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
        eventStartEditable: true, //The event can be dragged to a new day
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
        },
        eventDrop: function( event, delta, revertFunc, jsEvent, ui, view ){
            //Fires when an event is dragged and dropped onto a new day.
            alert('Event dragged');
//            $.ajax({
//                url: "<?php //echo base_url(); ?>//index.php/shift/editShift",
//                dataType: 'json',
//                data: {
//                    title: 'confirmMessages',
//                    deleted: deleted
//                },
//                success: function (result) {
//                    console.log('message confirm success');
//                },
//                error: function () {
//                    console.log('message confirm failed');
//                }
//            });
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
                    $('#' + tickbox).prop('checked', false);
                });
                newShifts.length = 0;
            }

            //Return all events for the clicked day
                dayEvents = $('#calendar').fullCalendar( 'clientEvents' ,function(event){
                    return moment(event.start).isSame(date, 'day');
                });
            dayEvents.forEach(function(event){
                //Populate the modal checkboxes, if a user is already working th selected day
                $('#' + event.userID).prop('checked', true);
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
                console.log(result[0].title);
                console.log(result[0].start);
                $('#calendar').fullCalendar('refetchEvents');
            },
            error: function () {
                alert("Oops! Something went wrong.");
            }
        });
    }else{
        console.log('RemoveShift');
        $.ajax({
            url: "<?php echo base_url(); ?>index.php/shift/remove_shiftDate",
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

</script>
