<?php

echo '<h1>Viewing all users</h1>';
foreach ($user as $user_data):
?>
    <h2><?php echo $user_data['forename']; ?></h2>
    <div class="main">
        <?php echo $user_data['password'] ?>
    </div>
    <p><a href="user/<?php echo $user_data['userID'] ?>">View User</a></p>
    <p><a href="shift/<?php echo $user_data['userID'] ?> ">View shifts </a></p>

<?php

endforeach

?>