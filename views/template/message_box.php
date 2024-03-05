<div class="message__box">
    <?php if (isset($s_message)): ?>
        <div class="success-message"><?php echo $s_message ?></div>
    <?php endif; ?>
    <?php if (isset($e_message)): ?>
        <div class="error-message"><?php echo $e_message ?></div>
    <?php endif; ?>
</div>