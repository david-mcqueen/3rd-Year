
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