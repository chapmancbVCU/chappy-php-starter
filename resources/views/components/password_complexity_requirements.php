<div>
    <h4>Password Requirements</h4>
    <ul class="pl-3">
        <?php if (env('SET_PW_MIN_LENGTH', false)): ?>
            <li>Minimum <?= env('PW_MIN_LENGTH', 12) ?> characters in length</li>
        <?php endif; ?>

        <?php if (env('SET_PW_MAX_LENGTH', false)): ?>
            <li>Maximum of <?= env('PW_MAX_LENGTH', 30) ?> characters in length</li>
        <?php endif; ?>

        <?php if (env('PW_UPPER_CHAR', false)): ?>
            <li>At least 1 upper case character</li>
        <?php endif; ?>

        <?php if (env('PW_LOWER_CHAR', false)): ?>
            <li>At least 1 lower case character</li>
        <?php endif; ?>

        <?php if (env('PW_NUM_CHAR', false)): ?>
            <li>At least 1 number</li>
        <?php endif; ?>

        <?php if (env('PW_SPECIAL_CHAR', false)): ?>
            <li>Must contain at least 1 special character</li>
        <?php endif; ?>  

        <li>Must not contain any spaces</li>
    </ul>
</div>
