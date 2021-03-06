<?php if ($sf_user->hasFlash('notice')): ?>
  <div class="flash_notice"><?php echo html_entity_decode($sf_user->getFlash('notice')) ?></div>
<?php elseif ($sf_user->hasFlash('error')): ?>
  <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
<?php endif; ?>

<h1>Tools</h1>
<div class="flash_caution"><a></a>Actions here cannot be undone. Proceed with caution.<a></a></div>

<h2>Project Nomination Form Deadline</h2>
<form enctype="multipart/form-data" action="<?php echo url_for('project/changeDeadline') ?>" method="POST" class="tool-form">
  After the deadline, the project nomination forms will be read-only for students.<br><br>
  Deadline: 
  <input id="date-picker" class="date-picker" name="deadline" type="text" value="<?php echo $deadline; ?>" />	
  <span class="smlInstruction">[YYYY-MM-DD]</span>
  <br><br>
  <input type="submit" value="Change Date" />
</form>

<h2>Set Email Domain</h2>
<form onSubmit="return confirm('Are You Sure?')" enctype="multipart/form-data" action="<?php echo url_for('project/changeDomain') ?>" method="POST" class="tool-form">
  This domain is used to create the email addresses of all users.<br><br>
  Email domain: 
  @<input name="domain" type="text" value="<?php echo $domain; ?>" /> 
  <span class="smlInstruction"> ie. griffithuni.edu.au</span>
  <br><br>
  <input type="submit" value="Change Domain" />
</form>

<h2>Import Students From File</h2>
<form onSubmit="return confirm('Are You Sure?')" enctype="multipart/form-data" action="<?php echo url_for('project/importStudents') ?>" method="POST" class="tool-form">
  Select a CSV File.  (max. file size: 1MB) <br><br><input name="studentFile" type="file" /><br><br>
  <input type="submit" value="Import" />
</form>

<h2>Export Database Information</h2>
<form enctype="multipart/form-data" onSubmit="return confirm('Are You Sure?')" action="<?php echo url_for('project/exportTables') ?>" method="POST" class="tool-form">
  <p>Export to CSV files... </p>
  <br/>
  <p style="margin-left:20px;">
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
  Note: To reset one user's password, edit them  <?php echo link_to("here", 'guard/users'); ?>  instead.<br><br>
  This will reset every students' passwords.  Emails will be sent out with their new ones.<br><br>
  This can take a while, so please be patient. <br><br>
  <input type="submit" value="Submit" />
</form>

<h2>Email Reminders</h2>
<form enctype="multipart/form-data" onSubmit="return confirm('Are You Sure?')" action="<?php echo url_for('project/emailReminders') ?>" method="POST" class="tool-form">
  This will email every student in the system that hasn't completed the form with a reminder to complete their forms.<br><br>
  This will not email them their passwords.<br><br>
  <input type="submit" value="Submit" />
</form>


<h2>Add Individual Student</h2>
<form enctype="multipart/form-data" onSubmit="return confirm('Are You Sure?')"  action="<?php echo url_for('project/addStudent') ?>" method="POST" class="tool-form formB">
  The system will also email the student his or her generated password to log-in. <br>
  <label for="snum">ID:</label>S<input class="" name="snum" type="text" value="" />	<br>
  <label for="f_name">First Name:</label><input class="" name="fName" type="text" />	<br>
  <label for="f_name">Last Name:</label><input class="" name="lName" type="text" />
  <br><br>
  <input type="submit" value="Add Student" />
</form>

<h2>Delete Student</h2>
<form enctype="multipart/form-data" onSubmit="return confirm('Are You Sure?')"  action="<?php echo url_for('project/deleteStudent') ?>" method="POST" class="tool-form formB">
  <label for="snum">ID:</label>S<input class="" name="snum" type="text" />	
  <br><br>
  <input type="submit" value="Delete Student" />
</form>

<h2> Reset an individual student's password</h2>
<form enctype="multipart/form-data" onSubmit="return confirm('Are You Sure?')"  action="<?php echo url_for('project/emailPassword') ?>" method="POST" class="tool-form formB">
  <label for="snum">ID:</label>S <input class="" name="snum" type="text" value="" />	<br>
  This will reset a single student's password.  An email will be sent out with their new one.<br><br>
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