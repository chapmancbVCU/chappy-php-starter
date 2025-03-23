<?php use Core\Lib\Utilities\Env; ?>

<div>
    <h4>Password Requirements</h4>
    <ul class="pl-3">
        <?php if (Env::get('SET_PW_MIN_LENGTH', false)): ?>
            <li>Minimum <?= Env::get('PW_MIN_LENGTH', 12) ?> characters in length</li>
        <?php endif; ?>

        <?php if (Env::get('SET_PW_MAX_LENGTH', false)): ?>
            <li>Maximum of <?= Env::get('PW_MAX_LENGTH', 30) ?> characters in length</li>
        <?php endif; ?>

        <?php if (Env::get('PW_UPPER_CHAR', false)): ?>
            <li>At least 1 upper case character</li>
        <?php endif; ?>

        <?php if (Env::get('PW_LOWER_CHAR', false)): ?>
            <li>At least 1 lower case character</li>
        <?php endif; ?>

        <?php if (Env::get('PW_NUM_CHAR', false)): ?>
            <li>At least 1 number</li>
        <?php endif; ?>

        <?php if (Env::get('PW_SPECIAL_CHAR', false)): ?>
            <li>Must contain at least 1 special character</li>
        <?php endif; ?>  

        <li>Must not contain any spaces</li>
    </ul>
</div>
