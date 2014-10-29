<?php

?>
<h3>Edit user details</h3>
<form action="<?php echo $user_data['userID']; ?>" autocomplete="off">
    <label for="forname">Forname</label>
    <input type="text" id="forname" value="<?php echo $user_data['forename']; ?>"></br>
    <label for="surname">Surname</label>
    <input type="text" id="surame" value="<?php echo $user_data['surname']; ?>"></br>
    <label for="passwordOld">Old Password</label>
    <input type="passwordOld" id="passwordOld" value=""></br>
    <label for="passwordNew">New Password</label>
    <input type="password" id="passwordNew"></br>
    <label for="passwordConfirm">Confirm Password</label>
    <input type="password" id="passwordConfirm"></br>
    <label for="level">Level</label>
    <input type="text" id="level" value ="<?php echo $user_data['levelName']; ?>" disabled></br>
    <label for="staffID">Staff ID</label>
    <input type="text" id="staffID" value ="<?php echo $user_data['staffID']; ?>" disabled></br>
    <input type="submit" value="Update">
</form>
