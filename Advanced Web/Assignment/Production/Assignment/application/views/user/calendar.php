<meta charset='utf-8' />
<link href='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>application/views/css/user/calendar.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/fullcalendar.print.css' rel='stylesheet' media='print' />

<script src='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/lib/jquery.min.js'></script>
<script src='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/lib/moment.min.js'></script>
<script src='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/fullcalendar.min.js'></script>
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
        missingShifts;

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
            //Stops the user going more than 3 months in the future.
            if (view.start > maxDate){
                $('#calendar').fullCalendar('gotoDate', maxDate);
                alert("Only 3 months forecast is available");
            }
        },
        eventAfterAllRender: function(view){
            calculateMissingShifts(view);

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
                    transitionPopup($("#warning"), false);
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
        }
    });

    $("#warning-close").click(function(){
        transitionPopup($("#warning"), false);
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

                var misingShiftMessage = "You are " + missingShifts + " shifts short for week commencing ";
                $("#missing-shift").html($("#missing-shift").html() + "</br>" + misingShiftMessage + nextWeek.format('DD-MM-YYYY'));
            }
            nextWeek = nextWeek.add(7, 'days');
        }
    };
});


</script>

<body>
<div class="container-fluid">
    <div class="row-fluid">


        <div class="col-xs-3 col-md-3 col-lg-3">
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
                    You can not add or remove a shift for this date.
                    Please see the <a href=''
                                      class='alert-link'
                                      data-toggle="modal"
                                      data-target=".bs-example-modal-lg">guidlines</a>
                </div>
                <div class="shifts" id="shifts">
                    <div class="alert alert-warning missing-shift"
                         id="missing-shift"
                         role="alert">Missing Shifts:</div>
                </div>


            </div>
        </div>
        <div class="col-xs-8 col-md-9 col-lg-9">
        <div id='loading'>loading...</div>
        <div id='calendar'></div>
        </div>
    </div>
</div>
<!--Modal's-->

<!--Modal explaining the shift guidelines-->
<div class="modal fade bs-example-modal-lg"
     tabindex="-1"
     ole="dialog"
     aria-labelledby="mylargeModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                Shift Guidlines
            </div>
            <div class="modal-body">
                Nurse:
                <ul>
                    <li>
                        Minimum 2 nurses per shift
                    </li>
                    <li>
                        No more than 5 shifts per week
                    </li>
                    <li>
                        Maximum of 1 shift per day
                    </li>
                </ul>
                Senior:
                <ul>
                    <li>
                        Minimum 1 senior nurse per shift
                    </li>
                    <li>
                        No more than 5 shifts per week
                    </li>
                    <li>
                        Maximum of 1 shift per day
                    </li>
                </ul>
                All rules are subject to management discression.
            </div>
        </div>
    </div>
</div>
</body>