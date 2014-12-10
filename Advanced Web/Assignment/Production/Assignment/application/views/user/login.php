<?php
/**
 * David McQueen
 * 10153465
 * December 2014
 */
?>

<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link href='<?php echo base_url(); ?>application/views/css/user/login.css' rel="stylesheet">

<div class="container">
    <div class="form">

        <?php
        //If errors are present, display them
        if(isset($errors)){
            echo '<div class="alert alert-danger" role="alert">' . $errors . '</div>';
        }
        ?>

        <form action="<?php echo base_url(); ?>index.php/VerifyLogin" method="POST" onsubmit="return validateForm()" name="loginForm" id="loginForm">
        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="text"
               size="20"
               id="username"
               name="username"
               placeholder="Username"
               required
               autofocus
               class="form-control"
               pattern="^([0-9]{4}|[a-zA-Z]\.[a-zA-Z]+)@nhs\.org$"
               title="staffID@nhs.org or initial.surname@nhs.org"
            />
        <input type="password"
               size="20"
               id="passowrd"
               name="password"
               required
               placeholder="Password"
               class="form-control"
            />
        <br/>
        <input type="submit"
               value="Login"
               class="btn btn-lg btn-primary btn-block"
            />
        </form>
    </div>
</div>
<script src='<?php echo base_url(); ?>application/third_party/fullcalendar-2.1.1/lib/jquery.min.js'></script>
<script type="text/javascript">
    function validateForm() {
        var usernameInput = document.forms["loginForm"]["username"].value,
            passwordInput = document.forms["loginForm"]["password"].value,
            usernameRegex = /^([0-9]{4}|[a-zA-Z]\.[a-zA-Z]+)@nhs\.org$/,
            success = true;

        //Ensure data has been entered
        if (usernameInput == null || usernameInput == "") {
            success = false;
        }else{
            //Ensure that the username matches the required email
            if (!usernameRegex.test(usernameInput)){
                success = false;
            }
        }

        //Ensure that the user has enetered a password
        //Dont need to check regex here, as they might have an old password which doesn't meet the password policy
        if (passwordInput == null || passwordInput == ""){
            success = false;
        }
        return success;

    }
</script>
<?php
/**
Regex Explanation:
    ^([0-9]{4}|[a-zA-Z]\.[a-zA-Z]+)@nhs\.org$

    ^                       Start of string
    [0-9]{4}                Contain 4 digits
    |                       OR
    [a-zA-Z]\.[a-zA-Z]+     Single character . string of characters
    @stu\.mmu\.ac\.uk$      End with the email address

*/
?>