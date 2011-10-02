<h1>Tools</h1>
<?php if ($sf_user->hasFlash('notice')): ?>
  <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
<?php endif; ?>
<div class="flash_notice">Actions here cannot be undone. Proceed with caution.</div>

<ul>
<li class="tool"><?php echo link_to('Delete All Students', 'project/clearAllStudents') ?></li>
<li class="tool"><?php echo link_to('Delete All Projects', 'project/clearAllProjects') ?></li>
</ul>