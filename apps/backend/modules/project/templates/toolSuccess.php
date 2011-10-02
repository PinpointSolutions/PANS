<h1>Tools</h1>
<?php if ($sf_user->hasFlash('notice')): ?>
  <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
<?php endif; ?>
<div class="flash_notice">Actions here cannot be undone. Proceed with caution.</div>

<h2>Import Students From File</h2>
<form enctype="multipart/form-data" action="<?php echo url_for('@importStudents') ?>" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
Select a CSV File.  (max. file size: 1MB) <br><br><input name="studentFile" type="file" /><br><br>
<input type="submit" value="Import" />
</form>

<ul>
<li class="tool"><?php echo link_to('Delete All Students', 'project/clearAllStudents') ?></li>
<li class="tool"><?php echo link_to('Delete All Projects', 'project/clearAllProjects') ?></li>
</ul>