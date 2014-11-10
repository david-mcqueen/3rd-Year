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
$(document).ready(function() {

    var maxDate = new Date(),
        deleteMessage,
        dayEvents,
        onShift,
        weekShiftCounter,
        monthEvents,
        nextWeek,
        weekEventsCalculate,
        weekShiftMissingCounter,
        missingShifts,
        isAdmin = <?php echo $isAdmin;?>;

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
            alert("Display popup modal asking which user to add a shift for");


                //Return all events for the clicked day
                dayEvents = $('#calendar').fullCalendar( 'clientEvents' ,function(event){
                    return moment(event.start).isSame(date, 'day');
                });

                onShift = false;
                dayEvents.forEach(function(event){
                    //For each of the calendar events on clicked day, determine if the user is already working
                    if (event.onShift == 1){
                        onShift = true;
                    }
                });

                //Return all events for the clicked week
                weekEvents = $('#calendar').fullCalendar( 'clientEvents' ,function(event){
                    return moment(event.start).isSame(date, 'week');
                });

                weekShiftCounter = 0;
                weekEvents.forEach(function(event){
                    //Count up all of the shifts the user is working for the clicked week
                    if (event.onShift == 1){
                        weekShiftCounter+=1;
                    }
                });

                if (!onShift && weekShiftCounter < 5){
                    transitionPopup($("#warning"), false);
                    transitionPopup($("#warning-future"), false);
                    // If the user is not already working that clicked day.
                    // AND they don't already have 5 shifts for the current week
                    // Attempt to add a new shift
                    $.ajax({
                        url: "<?php echo base_url(); ?>index.php/shift/addShift",
                        dataType: 'json',
                        data: {
                            title: '',
                            start: date.format()
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
                    transitionPopup($("#warning"), true);
                }

        }
    });

    $("#warning-close").click(function(){
        transitionPopup($("#warning"), false);
    });

    $("#warning-future-close").click(function(){
        transitionPopup($("#warning-future"), false);
    });

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

    function calculateMissingShifts(view){
        transitionPopup($("#missing-shift"), false);
        $( "#missing-shift").html("Missing Shifts:");
        monthEvents = $('#calendar').fullCalendar( 'clientEvents' ,function(event){
            //Get all events for the current month
            return moment(event.start).isSame($('#calendar').fullCalendar('getDate'), 'month');
        });

        nextWeek = moment(view.start); //Clone the moment so that we don't affect the original

        while(moment(nextWeek).isBefore(moment(view.intervalEnd))) {

            weekEventsCalculate = $('#calendar').fullCalendar('clientEvents', function (event) {
                return moment(event.start).isSame(nextWeek, 'week');
            });

            weekShiftMissingCounter = 0;
            weekEventsCalculate.forEach(function (event) {
                //Count up all of the shifts the user is working for the clicked week
                if (event.onShift == 1) {
                    weekShiftMissingCounter += 1;
                }
            });
            if (weekShiftMissingCounter < 5 && moment(nextWeek).isBefore(maxDate, 'week')) {
                missingShifts = (5 - weekShiftMissingCounter);
                setTimeout(function () {
                    transitionPopup($("#missing-shift"), true);
                }, 300);

                var misingShiftMessage = nextWeek.format('DD/MM') + ": You are " + missingShifts + " shift(s) short";
                $("#missing-shift").html($("#missing-shift").html() +  "</br>" + misingShiftMessage);
            }
            nextWeek = nextWeek.add(7, 'days');
        }
    };
});


</script>