<?php
/**
 * Created by PhpStorm.
 * User: Dave
 * Date: 06/11/14
 * Time: 10:51
 */


?>
<h3>Modify Personal Details</h3>
<form action="updateSettings" method="POST">
    <label for="staffID">Staff ID: </label>
    <input type="text" size="50" id="staffID" name="staffID" value="<?php echo $staffID ?>" disabled/>
    <br/>

    <label for="forename">Forename: </label>
    <input type="text" size="50" id="forename" name="forename" value="<?php echo $forename ?>" />
    <br/>

    <label for="surname">Surname: </label>
    <input type="text" size="50" id="surname" name="surname" value="<?php echo $surname ?>" />
    <br/>

    <label for="staffLevel">Staff Level: </label>
    <input type="text" size="50" id="staffLevel" name="staffLevel" value="<?php echo $levelName ?>" disabled/>
    <br/>

    <label for="emailAddress">Personal Email Address:</label>
    <input type="text" size="50" id="emailAddress" name="emailAddress" value="<?php echo $emailAddress ?>"/>
    <br/>

    <label for="phoneNumber">Phone Number:</label>
    <input type="text" size="20" id="phoneNumber" name="phoneNumber" value="<?php echo $phoneNumber ?>"/>
    <br/>

    <label for="address1">Address Line 1:</label>
    <input type="text" size="20" id="address1" name="address1" value="<?php echo $address1 ?>"/>
    <br/>

    <label for="address2">Address Line 2:</label>
    <input type="text" size="20" id="address2" name="address2" value="<?php echo $address2 ?>"/>
    <br/>

    <label for="city">City:</label>
    <input type="text" size="20" id="city" name="city" value="<?php echo $city ?>"/>
    <br/>

    <label for="postcode">Postcode:</label>
    <input type="text" size="20" id="postcode" name="postcode" value="<?php echo $postcode ?>"/>
    <br/>

    <label for="password">New Password:</label>
    <input type="password" size="20" id="password" name="password"/>
    <br/>

    <label for="confirmPassword">Confirm Password:</label>
    <input type="password" size="20" id="confirmPassword" name="confirmPassword"/>
    <br/>

    <input type="submit" value="Update Details"/>
</form>