<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php use_javascript('/sfFormExtraPlugin/js/jquery.autocompleter.js') ?>
<?php use_stylesheet('/sfFormExtraPlugin/css/jquery.autocompleter.css') ?>


<form class="form" action="<?php echo url_for('student/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?snum='.$form->getObject()->getSnum() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
<table>
  <tbody>
    <tr>
      <th>Student Number</th>
        <td><?php echo $sf_user->getUsername(); ?></td>
      </tr>
      <?php echo $form ?>
    </tbody>
  </table>
  
<?php /*
  <div class="action"><a href="<?php echo url_for('student/index') ?>">Back to Students</a></div>
  <?php if (!$form->getObject()->isNew()): ?>
    <div class="action"><?php echo link_to('Delete', 'student/delete?snum='.$form->getObject()->getSnum(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?></div>
  <?php endif; ?>
  */ ?>
  <label for="submit"><span class="req">(*) Required Field </span></label><input class="centered" name="submit" type="submit" value="Save"/>
</form>
