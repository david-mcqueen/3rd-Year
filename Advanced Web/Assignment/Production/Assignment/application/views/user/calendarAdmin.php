<?php
/**
 * David McQueen
 * 10153465
 * December 2014
 */

/**
 * Page containing visual displays which are only relevant for Admin users
 */
?>
<!--Modal's-->

<!--Modal displaying all staff members. Allowing for staff to be picked for shifts-->
<div class="modal fade bs-userSelection-modal-lg"
     tabindex="-1"
     ole="dialog"
     id="userSelection"
     aria-labelledby="mylargeModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                Pick staff to work this shift
            </div>
            <div class="modal-body">
                <input type="button"
                       value="Copy Day"
                       id="copyDay"
                       name="copyDay"
                    />
                <input type="button"
                       value="Paste Day"
                       id="pasteDay"
                       name="pasteDay"
                       class="hidden"
                    />
                </br>
                </br>
                <?php
                foreach($users as $user){
                    echo '<ul> <input id="newShift' . $user['userID'] . '"  name="' . $user['userID'] . '" value="' . $user['userID'] . '" type="checkbox" onclick="modifyShift(' . $user['userID'] . ', this)"> ';
                    echo $user['levelName'] . ': ' .  $user['forename'] . ' ' . $user['surname'];
                    echo '</ul>';
                }
                ?>
            </div>
        </div>
    </div>
</div>