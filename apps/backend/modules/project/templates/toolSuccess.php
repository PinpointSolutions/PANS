<?php
// this is a free script from  http://snipplr.com/view.php?codeview&id=3440 that has been slightly customised

// generates date selector form elements and retains this as a String $datePicker for use wherever desired

//For now this is here but not visible, will decide later as to whether to use. To see what it would look like just uncomment the php echo statement in the deadline form section

// NOTES - if we want to use this we have to use php "string date  ( string $format  [, int $timestamp = time()  ] )" to determine correct range and initial setting
//		we also want to concatonate the three values before sending to the database. This wil require affecting executeChangeDeadline() in the actions.class.php file

//can use either day numbers of day names
$months = array (1 => 'January', 'February', 'March', 'April', 'May', 'June','July', 'August', 'September', 'October', 'November', 'December');
$weekday = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
$days = range (1, 31);
$years = range (2005, 2015);//need to use php date() to correctly set

//**********************************************
$datePicker = '<p style="font-size:14px;line-height:140%;">This is an example of the datePicker script<br/>';
$datePicker .= 'We can use either day names or numbers<br/>';
$datePicker .= '<span style="font-style:italic">note: these are not communicating with the database</span><br/><br/>';
$datePicker .= " Weekday: <select name='weekday'>";//also apply css to layout better
foreach ($weekday as $value) {
	//we should use a conditional statement to decide which to keep
	$datePicker .= '<option value="'.$value.'">'.$value.'</option>\n';
} $datePicker .= '</select><br />';

$datePicker .= "Month: <select name='month'>";
foreach ($months as $value) {
	$datePicker .= '<option value="'.$value.'">'.$value.'</option>\n';
} $datePicker .= '</select><br />';

$datePicker .= "Day: <select name='day'>";
foreach ($days as $value) {
	$datePicker .= '<option value="'.$value.'">'.$value.'</option>\n';
} $datePicker .= '</select><br />';

$datePicker .= "Year: <select name='year'>";
foreach ($years as $value) {
	$datePicker .= '<option value="'.$value.'">'.$value.'</option>\n';
} 
$datePicker .= '</select></p>';
?>


<h1>Tools</h1>
<?php if ($sf_user->hasFlash('notice')): ?>
  <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
<?php elseif ($sf_user->hasFlash('error')): ?>
  <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
<?php endif; ?>
<div class="flash_caution"><a></a>Actions here cannot be undone. Proceed with caution.<a></a></div>

<h2>Project Nomination From Deadline</h2>
<form enctype="multipart/form-data" action="<?php echo url_for('project/changeDeadline') ?>" method="POST" class="tool-form">
After the deadline, the project nomination forms will be read-only for students.<br><br>
Deadline: 
<input id="date-picker" class="date-picker" name="deadline" type="text" value="<?php echo $deadline; ?>" />	
<br><br>

<?php 
// datePicker echo command -just for display purposes right now
//echo $datePicker.'<br/><br/>'; 
?>


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

<h2>Reset and Email All Students Passwords</h2>
<form enctype="multipart/form-data" onSubmit="return confirm('Are You Sure?')" action="<?php echo url_for('project/emailAllPasswords') ?>" method="POST" class="tool-form">
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
<li class="tool" ><?php echo link_to('Delete All Students', 'project/clearAllStudents', 'confirm=Delete All Students?') ?></li>
<li class="tool"><?php echo link_to('Delete All Projects', 'project/clearAllProjects', 'confirm=Delete All Projects?') ?></li>
</ul>
</div>