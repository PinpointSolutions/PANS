<h1>Tools</h1>
<?php if ($sf_user->hasFlash('notice')): ?>
  <div class="flash_notice"><?php echo html_entity_decode($sf_user->getFlash('notice')) ?></div>
<?php elseif ($sf_user->hasFlash('error')): ?>
  <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
<?php endif; ?>
<div class="flash_caution"><a></a>Actions here cannot be undone. Proceed with caution.<a></a></div>

<h2>Project Nomination From Deadline</h2>
<form enctype="multipart/form-data" action="<?php echo url_for('project/changeDeadline') ?>" method="POST" class="tool-form">
After the deadline, the project nomination forms will be read-only for students.<br><br>
Deadline: 
<input id="date-picker" class="date-picker" name="deadline" type="text" value="<?php echo $deadline; ?>" />	
<span class="smlInstruction">[YYYY-MM-DD]</span>
<br><br>

<input type="submit" value="Change Date" />
</form>

<h2>Import Students From File</h2>
<form enctype="multipart/form-data" action="<?php echo url_for('project/importStudents') ?>" method="POST" class="tool-form">
  <!-- <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />  -->
  Select a CSV File.  (max. file size: 1MB) <br><br><input name="studentFile" type="file" /><br><br>
  <input type="submit" value="Import" />
</form>

<h2>Export Database Information</h2>
<form enctype="multipart/form-data" onSubmit="return confirm('Are You Sure?')" action="<?php echo url_for('project/exportTables') ?>" method="POST" class="tool-form">
  <p>Export to CSV files... </p>
  <br/>
  <p style="margin-left:20px;">
	  <!-- <input type="hidden" name="MAX_FILE_SIZE" value="1000000" /> -->
	   <select name="infoType">
		<option value="projects">
			Project List
		</option>
		<option value="students" selected="true">
			Student List
		</option>
		<option value="groups">
			Group List
		</option>
		</select>
	   <input type="submit" value="Export"/>
  </p>
</form>

<h2>Reset and Email All Passwords</h2>
<form enctype="multipart/form-data" onSubmit="return confirm('This will erase all existing student passwords and generate whole new ones. Are You Sure?')" action="<?php echo url_for('project/emailAllPasswords') ?>" method="POST" class="tool-form">
  This will reset every students' passwords.  Emails will be sent out with their new ones.<br><br>
  <table>
    <tr>
      <td>Recipient Email domain</td><td><input type="text" name="subject" value="@griffithuni.edu.au" /></td>
    </tr>
  </table>
  <input type="submit" value="Submit" />
</form>

<h2>Delete Data</h2>
<div class="tool-form">
<ul>
<li class="tool" ><?php echo link_to('Delete All Students', 'project/clearAllStudents', 'confirm=Delete All Students?') ?></li>
<li class="tool"><?php echo link_to('Delete All Projects', 'project/clearAllProjects', 'confirm=Delete All Projects?') ?></li>
<li class="tool"><?php echo link_to('Delete All Groups', 'project/clearAllGroups', 'confirm=Delete All Groups?') ?></li>
</ul>
</div>