<?php
/*
 * php file which contains all functionality needed for the standard user
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

    var currentDate = new Date(),
        maxDate = new Date(),
        deleteMessage,
        dayEvents,
        onShift,
        weekShiftCounter,
        monthEvents,
        nextWeek,
        weekEventsCalculate,
        weekShiftMissingCounter,
        missingShifts,
        isAdmin = <?php echo $isAdmin;?>,
        displayInstructions = false;

    maxDate.setMonth(maxDate.getMonth() + 3); //Limit of 3 months in the future

    $('#calendar').fullCalendar({
        header: { //Define the buttons
            left: 'prev,next today',
            center: 'title',
            right: 'month'
        },
        firstDay:1, //Week starts on Monday
        defaultDate: Date(),
        weekNumbers: true,
        editable: true, //So users can click to add shifts
        eventLimit: true, // allow "more" link when too many events
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
            //Stops the user going more than 3 months in the future.
            if (view.start > maxDate){
                $('#calendar').fullCalendar('gotoDate', maxDate);
                setTimeout(function () {
                    transitionPopup($("#warning-future"), true)
                }, 300); //Add the display after a delay, so the earlier delete doesn't catch it.
            }
        },
        eventAfterAllRender: function(view){
            if(isAdmin !== 1){
                calculateMissingShifts(view);
            }
        },
        dayRender: function(date, cell){
            //Disables cells that are out of range
            if (date > maxDate){
                $(cell).addClass('disabled');
            }
        },
        eventClick: function(calEvent, jsEvent, view) {
            if (calEvent.onShift == 1){
                //Only process deletion if the user is on this shift

                deleteMessage = "Are you sure you want to remove your shift for date " + calEvent.shiftDate + "?";
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
            if(date > maxDate){
                //If the user has clicked beyond 3 months, display an error and don't add the shift
                transitionPopup($("#warning-future"), true);
            }else{

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
                            console.log(result[0].success);
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
        }
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
            if (weekShiftMissingCounter < 5
                && moment(nextWeek).isBefore(maxDate, 'week')
                && moment(nextWeek).isAfter(currentDate-7, 'week')) {
                    missingShifts = (5 - weekShiftMissingCounter);
                    setTimeout(function () {
                        transitionPopup($("#missing-shift"), true);
                    }, 300); //Add the display after a delay, so the earlier delete doesn't catch it.

                    var misingShiftMessage = nextWeek.format('DD/MM') + ": You are " + missingShifts + " shift(s) short";
                    $("#missing-shift").html($("#missing-shift").html() +  "</br>" + misingShiftMessage);
            }
            nextWeek = nextWeek.add(7, 'days');
        }
    };

    function confirmMessages(deleted){
        $.ajax({
            url: "<?php echo base_url(); ?>index.php/user/confirmMessages",
            dataType: 'json',
            data: {
                title: 'confirmMessages',
                deleted: deleted
            },
            success: function (result) {
                console.log('message confirm success');
            },
            error: function () {
                console.log('message confirm failed');
            }
        });
    }


    <?php
    $messageDeleted = false;
    $messageAdded = false;
        foreach ($userMessages as $message) {
            $date = new DateTime($message['shiftDate']);
            $formattedDate = date_format($date, 'D dS M Y');

            if($message['deleted'] == 1){
                echo '$("#warning-deleted").html($("#warning-deleted").html() + "</br>" + "' . $formattedDate . '");';
                $messageDeleted = true;
            }else{
                echo '$("#warning-added").html($("#warning-added").html() + "</br>" + "' . $formattedDate . '");';
                $messageAdded = true;
            }
        }
        if($messageDeleted == true){
            echo 'transitionPopup($("#warning-deleted"), true);';
        }
        if($messageAdded == true){
            echo 'transitionPopup($("#warning-added"), true);';
        }
?>

    //Populate the instructions for the user
    $('#instructionsBody').html(' <li>Click on a shift you are working to remove yourself from that shift.</li>' +
    '<li>Click on a day you are not currently working to add a new shift.</li>');

    $("#warning-close").click(function(){
        transitionPopup($("#warning"), false);
    });

    $("#warning-future-close").click(function(){
        transitionPopup($("#warning-future"), false);
    });

    $("#warning-added-close").click(function(){
        transitionPopup($("#warning-added"), false);
        confirmMessages(0);
        //Ajax to the DB so we know the messages have been read
    });

    $("#warning-deleted-close").click(function(){
        transitionPopup($("#warning-deleted"), false);
        confirmMessages(1);
        //Ajax to the DB so we know the messages have been read
    });

    $("#instructionsHeader").click(function(){
        displayInstructions = !displayInstructions;
        transitionPopup($("#instructionsBody"), displayInstructions);
    });

});
</script>