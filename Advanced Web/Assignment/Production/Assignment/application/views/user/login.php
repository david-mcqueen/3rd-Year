Home Page - Login

<!--<form action="index.php/pages/login">-->
<!--    <label for="userName">User Name: </label>-->
<!--    <input type="text" name=userName id="userName" placeholder="Username">-->
<!--    </br>-->
<!--    <label for="password">Password: </label>-->
<!--    <input type="password" name="password" id="password">-->
<!--    </br>-->
<!--    <input type="submit" name="submit" value="Login!">-->
<!--</form>-->

<?php echo validation_errors(); ?>
<?php echo form_open('index.php/VerifyLogin'); ?>
<label for="username">Username:</label>
<input type="text"
       size="20"
       id="username"
       name="username"
       required
       pattern="^([0-9]{4}|[a-zA-Z]\.[a-zA-Z]+)@nhs\.org$"
    />
See popup for the correct format
<br/>
<label for="password">Password:</label>
<input type="password"
       size="20"
       id="passowrd"
       name="password"
       required
    />
<br/>
<input type="submit"
       value="Login"
    />
</form>
<?php
/*
Regex Explanation:
    ^([0-9]{4}|[a-zA-Z]\.[a-zA-Z]+)@nhs\.org$

    ^                       Start of string
    [0-9]{4}                Contain 4 digits
    |                       OR
    [a-zA-Z]\.[a-zA-Z]+     Single character . string of characters
    @stu\.mmu\.ac\.uk$      End with the email address

*/
?>