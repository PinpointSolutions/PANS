<h1>Tools</h1>
<?php if ($sf_user->hasFlash('notice')): ?>
  <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
<?php endif; ?>
<div class="flash_notice">Actions here cannot be undone. Proceed with caution.</div>

<h2>Project Nomination From Deadline</h2>
<form enctype="multipart/form-data" action="<?php echo url_for('student/changeDeadline') ?>" method="POST" class="tool-form">
After the deadline, the project nomination forms will be read-only for students.<br><br>
Deadline: <input name="deadline" type="text" value="<?php echo $deadline; ?>" /><br><br>
<input type="submit" value="Change Date" />
</form>

<h2>Import Students From File</h2>
<form enctype="multipart/form-data" action="<?php echo url_for('student/importStudents') ?>" method="POST" class="tool-form">
  <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
  Select a CSV File.  (max. file size: 1MB) <br><br><input name="studentFile" type="file" /><br><br>
  <input type="submit" value="Import" />
</form>

<h2>Export information</h2>
<form enctype="multipart/form-data" action="<?php echo url_for('project/exportProjects') ?>" method="POST" class="tool-form">
  <p>Export various types of information to CSV files. </p>
  <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
   <label>Export project list:  </label> <input type="submit" value="Export" />
</form>

<h2>Reset and Email All Students Passwords</h2>
<form enctype="multipart/form-data" action="<?php echo url_for('student/emailAllPasswords') ?>" method="POST" class="tool-form">
  This will reset every students' passwords.  Emails will be sent out with their new ones.  (Dev: This doesn't work on your local machine.)<br><br>
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
<li class="tool"><?php echo link_to('Delete All Students', 'project/clearAllStudents') ?></li>
<li class="tool"><?php echo link_to('Delete All Projects', 'project/clearAllProjects') ?></li>
</ul>
</div>