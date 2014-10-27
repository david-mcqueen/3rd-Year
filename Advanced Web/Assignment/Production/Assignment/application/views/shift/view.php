<?php
/*
 * This is the page which displays each of the shifts
 */
foreach ($shift as $shift_data):
    echo '<h2> Shift ID:'.$shift_data['shiftID'].'</h2>';
    echo 'Shift date: ' . $shift_data['shiftDate'];

endforeach ?>