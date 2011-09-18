<h1>Import Students From File</h1>
<form enctype="multipart/form-data" action="<?php echo url_for('@importStudents') ?>" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
Select a CSV File.  (max. file size: 1MB) <br><br><input name="studentFile" type="file" /><br><br>
<input type="submit" value="Import" />
</form>