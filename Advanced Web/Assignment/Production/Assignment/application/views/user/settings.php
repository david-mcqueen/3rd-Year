<?php
?>
<style>

    body {
        margin: 40px 10px;
        padding: 0;
        font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
        font-size: 14px;
    }

    input:required:invalid,
    input:focus:invalid { border-color:red; }

</style>

<h3>Modify Personal Details</h3>

<form action="updateSettings" method="POST" onsubmit="return validateForm();">
    <label for="staffID">Staff ID: </label>
    <input type="text"
           size="20"
           id="staffID"
           name="staffID"
           value="<?php echo $staffID ?>"
           disabled
        />
    <br/>

    <label for="forename">Forename: </label>
    <input type="text"
           size="20"
           id="forename"
           name="forename"
           placeholder="Forename"
           required value="<?php echo $forename ?>"
        />
    <br/>

    <label for="surname">Surname: </label>
    <input type="text"
           size="20"
           id="surname"
           name="surname"
           placeholder="Surname"
           required value="<?php echo $surname ?>"
        />
    <br/>

    <label for="staffLevel">Staff Level: </label>
    <input type="text"
           size="20"
           id="staffLevel"
           name="staffLevel"
           value="<?php echo $levelName ?>"
           disabled
        />
    <br/>

    <label for="emailAddress">Personal Email Address:</label>
    <input type="email"
           size="50"
           id="emailAddress"
           name="emailAddress"
           placeholder="Email"
           value="<?php echo $emailAddress ?>"
        />
    <br/>

    <label for="phoneNumber">Phone Number:</label>
    <input type="number"
           size="20"
           id="phoneNumber"
           name="phoneNumber"
           placeholder="Phone Number"
           value="<?php echo $phoneNumber ?>"
        />
    <br/>

    <label for="address1">Address Line 1:</label>
    <input type="text"
           size="20"
           id="address1"
           name="address1"
           placeholder="Address"
           value="<?php echo $address1 ?>"
        />
    <br/>

    <label for="address2">Address Line 2:</label>
    <input type="text"
           size="20"
           id="address2"
           name="address2"
           placeholder="Line 2"
           value="<?php echo $address2 ?>"
        />
    <br/>

    <label for="city">City:</label>
    <input type="text"
           size="20"
           id="city"
           name="city"
           placeholder="City"
           value="<?php echo $city ?>"
        />
    <br/>

    <label for="postcode">Postcode:</label>
    <input type="text"
           size="20"
           id="postcode"
           name="postcode"
           placeholder="Postcode"
           value="<?php echo $postcode ?>"
        />
    <br/>
    <br/>

    Only enter a password if you want to change your current one.</br>
    <label for="password">New Password:</label>
    <input type="password"
           size="20"
           id="password"
           name="password"
           pattern="(?=^.{7,}$)((?=.*\d))(?=.*[A-Z])(?=.*[a-z]).*$"
        />

    <br/>

    <label for="confirmPassword">Confirm Password:</label>
    <input type="password"
           size="20"
           id="confirmPassword"
           name="confirmPassword"
           pattern="(?=^.{7,}$)((?=.*\d))(?=.*[A-Z])(?=.*[a-z]).*$"
        />
    <br/>
    <?php
    /*
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
        />
</form>

<script type="text/javascript" src='http://code.jquery.com/jquery-1.11.1.min.js'></script>
<script>
    $(document).ready(function() {
        var password = $("#password"),
            confirmPassword = $("#confirmPassword");

        confirmPassword.keyup(function () {
            //Check that the passwords match
            if(password[0].value == confirmPassword[0].value){

            }

        });
    });

    function validateForm(){

        alert('Doesnt submit due to return false form validation');
        return true;
    }
</script>