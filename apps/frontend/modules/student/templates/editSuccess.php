<?php if ($sf_user->hasFlash('notice')): ?>
  <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
<?php endif; ?>

<h1>Project Nomination Form</h1>

<?php include_partial('form', array('form' => $form)) ?>
