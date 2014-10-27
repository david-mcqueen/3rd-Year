<?php
    /*
     * This is the main index page for the shifts
     */
    foreach ($shift as $shift_data):
?>


    <h2>Shift ID: <?php echo $shift_data['shiftID']; ?></h2>
    <div class="main">
        Shift Date: <?php echo $shift_data['shiftDate'] ?>
    </div>
    <p><a href="shift/<?php echo $shift_data['shiftID'] ?>">View shift</a></p>

<?php
    endforeach
?>