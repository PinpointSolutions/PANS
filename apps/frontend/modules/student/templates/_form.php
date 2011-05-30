<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('student/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?snum='.$form->getObject()->getSnum() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tbody>
      <?php echo $form ?>
    </tbody>
  </table>
  <div class="action"><a href="<?php echo url_for('student/index') ?>">Back to list</a></div>
  <?php if (!$form->getObject()->isNew()): ?>
    <div class="action"><?php echo link_to('Delete', 'student/delete?snum='.$form->getObject()->getSnum(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?></div>
  <?php endif; ?>
  <input class="actionButton" type="submit" value="Save" />
</form>