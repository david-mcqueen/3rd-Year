<meta charset='utf-8' />

<script src='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/lib/jquery.min.js'></script>
<link href='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/lib/moment.min.js'></script>

<script src='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/fullcalendar.min.js'></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {

    var maxDate = new Date(),
        deleteMessage,
        dayEvents,
        onShift,
        weekShiftCounter;

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
                            alert("Oops! Something went wrong");
                        }
                    });
                }
            }

        },
        dayClick: function(date, jsEvent, view) {
            if(date > maxDate){
                //If the user has clicked beyond 3 months, display an error and don't add the shift
                alert("You can only add shifts for 3 months from the current date.");
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
                            $('#calendar').fullCalendar('refetchEvents');
                        },
                        error: function () {
                            alert("Oops! Something went wrong.");
                        }
                    });
                }else{
                    $("#warning").removeClass('hidden');
                    //$("#warning").html("You cant add a shift for this date. Please see the <a href='#' class='alert-link'>guidlines</a>");
                }
            }
        }
    });

    $("#warning-close").click(function(){
        $("#warning").addClass('hidden');
    });

});



</script>
<style>

    body {
        margin-left: 10px;
        margin-right: 10px;
        padding: 0;
        font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
        font-size: 14px;
    }

    #calendar, #messages {
        max-width: 900px;
        margin: 0 auto;
        margin-bottom: 40px;
        position: relative;
    }
    #messages{

    }

    .disabled {
        background-color: #999999;
        color: #999999;
        cursor: default;
    }
    .warning.hidden{
        display: none;
    }
    .modal-backdrop{
        z-index:0;
    }

</style>
<body>
<div class="well well-sm">Shift messages will go here</div>
<div class="alert alert-warning" role="alert">use alert instead?</div>

<!--User messages-->
<div id="messages">

    <!--Message: Can't add a shift due to breaking guidlines-->
    <div class="alert alert-danger alert-dismissible warning hidden"
         id="warning"
         role="alert">
        <button type="button"
                class="close"
                id="warning-close">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
        </button>
        You can not add a shift for this date.
        Please see the <a href=''
                          class='alert-link'
                          data-toggle="modal"
                          data-target=".bs-example-modal-sm">guidlines</a>
    </div>

</div>
<div id='calendar'></div>

<!--Modal's-->

<!--Modal explaining the shift guidelines-->
<div class="modal fade bs-example-modal-sm"
     tabindex="-1"
     ole="dialog"
     aria-labelledby="mySmallModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                Shift Guidlines
            </div>
            <div class="modal-body">
                Nurse Guidelines
                <ul>
                    <li>
                        There must be at least 2 nurses per shift
                    </li>
                </ul>
                Senior Nurse Guidelines
                <ul>
                    <li>
                        There must be at least 1 senior nurse per shift
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
</body>