<?php
/*
 *
 */
echo '<h1>All Shifts for date</h1>';
foreach ($shift as $shift_data):
    ?>


    <h2>Number on shift: <?php echo $shift_data['shiftNumbers']; ?></h2>
    <div class="main">
        Shift Date: <?php echo $shift_data['shiftDate'] ?>
        </br>
        Staff Level: <?php echo $shift_data['levelName'] ?>
    </div>

<?php
endforeach
?>