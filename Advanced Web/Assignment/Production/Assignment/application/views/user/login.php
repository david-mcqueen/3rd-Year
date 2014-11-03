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
<input type="text" size="20" id="username" name="username"/>
<br/>
<label for="password">Password:</label>
<input type="password" size="20" id="passowrd" name="password"/>
<br/>
<input type="submit" value="Login"/>
</form>