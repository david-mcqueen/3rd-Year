<?php
/*
 *
 */
echo '<h1>Welcome, *User Name*</h1>';
?>

<meta charset='utf-8' />
<link href='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/lib/moment.min.js'></script>
<script src='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/lib/jquery.min.js'></script>
<script src='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/fullcalendar.min.js'></script>
<script>



        $(document).ready(function() {

            var maxDate = new Date();
            maxDate.setDate(maxDate.getDate() + 90);
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                defaultDate: Date(),
                editable: true,
                eventLimit: true, // allow "more" link when too many events
                events: {
                    //url: 'http://localhost/php/get-events.php?',
                    //url: 'http://localhost/fullcalendar-2.1.1/demos/php/get-events.php?',
                    //url: '<?php echo base_url(); ?>application/views/shift/functions/getCalendar.php?',
                    url: '<?php echo base_url(); ?>index.php/shift/getCalendar',
                    error: function(textStatus, errorThrown) {
                        $('#script-warning').show();
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

    #script-warning {
        display: none;
        background: #eee;
        border-bottom: 1px solid #ddd;
        padding: 0 10px;
        line-height: 40px;
        text-align: center;
        font-weight: bold;
        font-size: 12px;
        color: red;
    }
    .disabled {
        background-color: #999999;
        color: #FFFFFF;
        cursor: default;
    }


</style>
<body>

<div id='script-warning'>
    <code>php/get-events.php</code> must be running.
</div>
<div id='calendar'></div>
</body>