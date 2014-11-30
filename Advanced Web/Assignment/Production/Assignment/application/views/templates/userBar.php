<?php
/**
 * David McQueen
 * 10153465
 * December 2014
 */
?>
<div class="page-header">
<?php
echo '<h1>Welcome, ' .  $forename .' ' . $surname . '</h1>';
?>
</div>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link href='<?php echo base_url(); ?>application/views/css/templates/userBar.css' rel="stylesheet">
<div class="btn-group buttons">
    <a class="btn btn-default" href="calendar"><span class="glyphicon glyphicon-calendar"> Calendar </span></a>
    <a class="btn btn-default" href="settings"><span class="glyphicon glyphicon-user"> Settings</span></a>
    <a class="btn btn-default" href="logout"><span class="glyphicon glyphicon-off"> Logout</span></a>
</div>
</br>
</br>
