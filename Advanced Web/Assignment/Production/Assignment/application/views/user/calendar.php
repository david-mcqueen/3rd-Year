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
                    You can not peform this action for this shift and/or date.
                    Please see the <a href=''
                                      class='alert-link'
                                      data-toggle="modal"
                                      data-target=".bs-example-modal-lg">guidlines</a>
                </div>

                <!--Message: Can't add a shift due to far in advance-->
                <div class="alert alert-danger alert-dismissible warning-future hidden"
                     id="warning-future"
                     role="alert">
                    <button type="button"
                            class="close"
                            id="warning-future-close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    You can only book shifts upto 3 months in advance.
                    Please see the <a href=''
                                      class='alert-link'
                                      data-toggle="modal"
                                      data-target=".bs-example-modal-lg">guidlines</a>
                </div>

                <!--Message: Shift deleted by Admin-->
                <div class="alert alert-info alert-dismissible warning-deleted hidden"
                     id="warning-deleted"
                     role="alert">
                    <button type="button"
                            class="close"
                            id="warning-deleted-close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    Admin have deleted the following shifts:
                </div>

                <!--Message: Shift added by Admin-->
                <div class="alert alert-info alert-dismissible warning-added hidden"
                     id="warning-added"
                     role="alert">
                    <button type="button"
                            class="close"
                            id="warning-added-close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    Admin have added the following shifts:
                </div>

                <!--Message: Shift modified by Admin-->
                <div class="alert alert-info alert-dismissible warning-modified hidden"
                     id="warning-modified"
                     role="alert">
                    <button type="button"
                            class="close"
                            id="warning-modified-close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    Admin have modified the following shifts:
                </div>


                <!--Message: Displays each week a shift is missing-->
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
                Shifts can only be booked upto 3 months in advance.</br>
                All rules are subject to management discression.
            </div>
        </div>
    </div>
</div>
</body>