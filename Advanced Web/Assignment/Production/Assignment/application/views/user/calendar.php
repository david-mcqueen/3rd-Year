<?php
/*
 *
 */
echo '<h1>Welcome, ' .  $forename .' ' . $surname . '</h1>';
?>

<meta charset='utf-8' />
<link href='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/lib/moment.min.js'></script>
<script src='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/lib/jquery.min.js'></script>
<script src='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/fullcalendar.min.js'></script>
<script>

$(document).ready(function() {

    var maxDate = new Date(),
        deleteMessage,
        dayEvents,
        onShift;

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
            //Stops the user going more than 3 months in the future.
            if (view.start > maxDate){
                $('#calendar').fullCalendar('gotoDate', maxDate);
                alert("Only 3 months forecast is available");
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

                deleteMessage = "Are you sure you want to remove your shift from date " + calEvent.shiftDate + "?";
                if(confirm (deleteMessage)){
                    $.ajax({
                        url: "<?php echo base_url(); ?>index.php/shift/removeShift",
                        dataType: 'json',
                        data: {
                            id: calEvent.id
                        },
                        success: function (result) {
                            if (!result[0].success){
                                alert(result[0].errors);
                            }else{
                                $('#calendar').fullCalendar('refetchEvents');
                            }
                        },
                        error: function () {
                            alert("Oops! Something didn't work");
                        }
                    });
                }
            }

        },
        dayClick: function(date, jsEvent, view) {
            dayEvents = $('#calendar').fullCalendar( 'clientEvents' ,function(event){
                return moment(event.start).isSame(date, 'day'); //Return all events for the clicked day
            });
            onShift = false;
            dayEvents.forEach(function(entry){
                //For each of the calendar events on clicked day, determine if the user is already working
                if (entry.onShift == 1){
                    onShift = true;
                }
            });
            if (!onShift){
                //If the user is not already working. Attempt to add a new shift
                $.ajax({
                    url: "<?php echo base_url(); ?>index.php/shift/addShift",
                    dataType: 'json',
                    data: {
                        title: '',
                        start: date.format()
                    },
                    success: function (result) {
                        console.log(result[0].title);
                        $('#calendar').fullCalendar('refetchEvents');
                    },
                    error: function () {
                        alert("Oops! Something didn't work");
                    }
                });
            }
        }
    });
});
</script>
<style>

    body {
        margin: 40px 10px;
        padding: 0;
        font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
        font-size: 14px;
    }

    #calendar {
        max-width: 900px;
        margin: 0 auto;
    }

    .disabled {
        background-color: #999999;
        color: #FFFFFF;
        cursor: default;
    }

</style>
<body>


<div id='calendar'></div>
</body>