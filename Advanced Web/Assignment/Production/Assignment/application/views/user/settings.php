<?php
/**
* David McQueen
* 10153465
* December 2014
*/
?>
<link href='<?php echo base_url(); ?>application/views/css/user/settings.css' rel="stylesheet">
<script src='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/lib/jquery.min.js'></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<div class="container">
    <?php
    //Display Either Success or Validation error message
    if(isset($messages) && $messages != ''){
        if($messages == 'Success'){
            echo '</br><div class="alert alert-success" role="alert"><strong>' . $messages . '</strong></div>';
        }else{
            echo '</br><div class="alert alert-danger" role="alert"><strong>' . $messages . '</strong></div>';
        }
    }
    ?>

    <h3>Personal Details</h3>

    <form action="settingsUpdate" method="POST" class="form-horizontal" role="form" onsubmit="return validateForm()" name="settingsForm" id="settingsForm">

        <div class="form-group">
        <label for="staffID" class="control-label col-sm-2">Staff ID: </label>
            <div class="col-sm-10">
                <input type="text"
                       size="20"
                       id="staffID"
                       class="form-control"
                       name="staffID"
                       value="<?php echo $staffID ?>"
                       disabled
                    />
            </div>
        </div>

        <div class="form-group">
        <label for="forename" class="control-label col-sm-2">Forename: </label>
            <div class="col-sm-10">
                <input type="text"
                       size="20"
                       id="forename"
                       class="form-control"
                       name="forename"
                       placeholder="Forename"
                       required
                       value="<?php echo $forename ?>"
                    />
            </div>
        </div>

        <div class="form-group">
        <label for="surname" class="control-label col-sm-2">Surname: </label>
            <div class="col-sm-10">
                <input type="text"
                       size="20"
                       id="surname"
                       class="form-control"
                       name="surname"
                       placeholder="Surname"
                       required
                       value="<?php echo $surname ?>"
                    />
            </div>
        </div>

        <div class="form-group">
        <label for="staffLevel" class="control-label col-sm-2">Staff Level: </label>
            <div class="col-sm-10">
                <input type="text"
                       size="20"
                       id="staffLevel"
                       class="form-control"
                       name="staffLevel"
                       value="<?php echo $levelName ?>"
                       disabled
                    />
            </div>
        </div>

        <div class="form-group">
        <label for="emailAddress" class="control-label col-sm-2">Personal Email Address:</label>
            <div class="col-sm-10">
                <input type="email"
                       size="50"
                       id="emailAddress"
                       class="form-control"
                       name="emailAddress"
                       placeholder="Personal Email"
                       value="<?php echo $emailAddress ?>"
                    />
            </div>
        </div>

        <div class="form-group">
        <label for="phoneNumber" class="control-label col-sm-2">Phone Number:</label>
            <div class="col-sm-10">
                <input type="number"
                       size="20"
                       id="phoneNumber"
                       class="form-control"
                       name="phoneNumber"
                       placeholder="Phone Number"
                       value="<?php echo $phoneNumber ?>"
                    />
            </div>
        </div>

        <div class="form-group">
        <label for="address1" class="control-label col-sm-2">Address Line 1:</label>
            <div class="col-sm-10">
                <input type="text"
                       size="20"
                       id="address1"
                       class="form-control"
                       name="address1"
                       placeholder="Address"
                       value="<?php echo $address1 ?>"
                    />
            </div>
        </div>

        <div class="form-group">
        <label for="address2" class="control-label col-sm-2">Address Line 2:</label>
            <div class="col-sm-10">
                <input type="text"
                       size="20"
                       id="address2"
                       class="form-control"
                       name="address2"
                       placeholder="Address 2"
                       value="<?php echo $address2 ?>"
                    />
            </div>
        </div>

        <div class="form-group">
        <label for="city" class="control-label col-sm-2">City:</label>
            <div class="col-sm-10">
                <input type="text"
                       size="20"
                       id="city"
                       class="form-control"
                       name="city"
                       placeholder="City"
                       value="<?php echo $city ?>"
                    />
            </div>
        </div>

        <div class="form-group">
        <label for="postcode" class="control-label col-sm-2">Postcode:</label>
            <div class="col-sm-10">
                <input type="text"
                       size="20"
                       id="postcode"
                       class="form-control"
                       name="postcode"
                       placeholder="Postcode"
                       value="<?php echo $postcode ?>"
                    />
            </div>
        </div>

        </br>
        <div class="alert alert-warning"
             id="passwordChangeMessage"
             role="alert">
            <strong>Only enter a new password if you want to change your current one.</strong>
            <a href=''
               class='alert-link'
               data-toggle="modal"
               data-target=".bs-example-modal-lg">See the Password Requirements</a>
        </div>

        <div class="form-group">
        <label for="password" class="control-label col-sm-2">New Password:</label>
            <div class="col-sm-10">
                <input type="password"
                       size="20"
                       id="password"
                       class="form-control"
                       name="password"
                       pattern="(?=^.{7,}$)((?=.*\d))(?=.*[A-Z])(?=.*[a-z]).*$"
                    />
            </div>
        </div>

        <div class="form-group">
        <label for="confirmPassword" class="control-label col-sm-2">Confirm Password:</label>
            <div class="col-sm-10">
                <input type="password"
                       size="20"
                       id="confirmPassword"
                       class="form-control"
                       name="confirmPassword"
                       pattern="(?=^.{7,}$)((?=.*\d))(?=.*[A-Z])(?=.*[a-z]).*$"
                    />
            </div>
        </div>
        <?php
        /**
        Regex Explanation:

            (?=^.{7,}$)((?=.*\d))(?=.*[A-Z])(?=.*[a-z]).*$

            (?=^.{7,}$)     = The string must be at least 7 in length
            ((?=.*\d))      = Must contain a digit
            (?=.*[A-Z])     = Must contain an Uppercase character
            (?=.*[a-z]).*$  = Must contain a Lowercase character

        */
        ?>

        <input type="submit"
               value="Update Details"
               class="btn btn-lg btn-primary btn-block"
            />
    </form>
</div>

<!--Modal with password guidelines-->
<div class="modal fade bs-example-modal-lg"
     tabindex="-1"
     ole="dialog"
     aria-labelledby="mylargeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                Password Guidlines
            </div>
            <div class="modal-body">
                Passwords must:
                <ul>
                    <li>
                        Be at least 7 characters long
                    </li>
                    <li>
                        Contain at least 1 upper case character
                    </li>
                    <li>
                        Contain at least 1 lower case character
                    </li>
                    <li>
                        Contain at least 1 number
                    </li>
                </ul>
            </div>
        </div>
    </div>
    </div>

<script type="text/javascript" src='http://code.jquery.com/jquery-1.11.1.min.js'></script>
<script>
    $(document).ready(function() {
        var password = $("#password"),
            confirmPassword = $("#confirmPassword");

        password.keyup(function () {
                checkPasswords();
        });

        confirmPassword.keyup(function () {
                checkPasswords();
        });

    });

    function checkPasswords(){
        //Check that the 2 passwords match
        if($("#password").val()== $("#confirmPassword").val()){
            console.log("passwords match");
            document.getElementById('confirmPassword').setCustomValidity('');
        }else{
            console.log("passwords do not match");
            document.getElementById('confirmPassword').setCustomValidity('Passwords do not match');
        }
    }

    function validateForm() {
        var forenameInput = document.forms["settingsForm"]["forename"].value,
            surnameInput = document.forms["settingsForm"]["surname"].value,
            passwordInput = document.forms["settingsForm"]["password"].value,
            passwordRegex = /(?=^.{7,}$)((?=.*\d))(?=.*[A-Z])(?=.*[a-z]).*$/,
            success = true;

        //Ensure data has been entered
        if (forenameInput == null || forenameInput == "") {
            success = false;
        }

        //Ensure data has been entered
        if (surnameInput == null || surnameInput == "") {
            success = false;
        }

        if (passwordInput != null && passwordInput != ""){
            //Ensure that the password matches the password policy
            if (!passwordRegex.test(passwordInput)){
                success = false;
            }
        }
        return success;

    }
</script>