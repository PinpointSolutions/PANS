<?php

require_once dirname(__FILE__).'/../lib/projectGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/projectGeneratorHelper.class.php';

/**
 * Admin view project and student actions.
 *
 * @package    PANS
 * @subpackage project
 * @author     Pinpoint Solutions
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class projectActions extends autoProjectActions
{
  ////////////////////////////////////////////////////////////////////////
  // Pages 

  // Admin tools page
  // Do not modify or remove this function
  public function executeTool(sfWebRequest $request)
  {
    $this->email = $this->getUser()->getGuardUser()->getEmailAddress();

    try {
      $this->deadline = Doctrine_Core::getTable('NominationRound')
        ->createQuery('a')
        ->fetchOne();
    } catch (Exception $e) {
      $this->deadline = null;
    }

    if (!$this->deadline) {
      $this->deadline = 'YYYY-MM-DD';
    } else {
      $this->deadline = $this->deadline->getDeadline();
    }
  }
  
  // Admin View for the Group Page
  public function executeGroup(sfWebRequest $request)
  {
    $this->groups = Doctrine_Core::getTable('ProjectAllocation')
      ->createQuery('a')
      ->execute();
  }

  ////////////////////////////////////////////////////////////////////////
  // Scripts 
  
  // Export Project table to CSV file
  public function executeExportProjects(sfWebRequest $request)
  {	
	//TODO:
		//update formatting to be easier to treat, for example escapeSlashes to stop injection
		//  QUESTION- do we want this easily readable in itself? 
			//would not acutally be that much more work, just have to remember consistency is key if we want to import again
			//we can always ignore our extra labels so long as we know where they would appear
			// AND/OR... maybe an option for a formatted list, for printing purposes mainly but we could treat it to be importable
				// very much optional requirement- but i love the idea
  
  
	//echo $request->getPathInfo()  ;//echo $request->getHttpHeader()  ;//echo $request->getPostParameters() ;//echo $request->getPathInfoArray() ;
	//echo $request->getRequestContext() ;//echo $request->getPostParameters() ;

    // Setup the connection
    $conn = Doctrine_Manager::getInstance();
	
	// Setup a variable to catch our data to export
	$info = '';
	
	// Then we grab the value of the drop down box
    $opt = $request->getPostParameter('infoType');
	
	// And we use this to decide which info to export
	if($opt=='projects')
	{
		$rows = Doctrine_Core::getTable('Project')->findAll();
    //have first row descriptions outside of loop, use a "," for a new column
    $info .= "ID," . "Title," . "Organisation," . "Description," . "More Info," . "GPA Cutoff," . "Major ID's," . "Skill Set ID's" . "\n";
	    foreach($rows as $r) {
	    
	      $info .=  $r['id'] . ",";
	      $info .=  $r['title'] . ",";
	      $info .=  $r['organisation'] . ",";
	      $info .=  $r['description'] . ",";
	      $info .=  $r['has_additional_info'] . ",";
	      $info .=  $r['has_gpa_cutoff'] . ",";
	      $info .=  $r['major_ids'] . ",";
	      $info .=  $r['skill_set_ids'] . ", \n";           
	    }
	
	}
	else if($opt=='students')
	{
		$rows = Doctrine_Core::getTable('StudentUser')->findAll();
      $info .= "S Number," . "First Name," . "Last Name," . "Pass/Fail PM," . "Degree," . "Major," . "Skills," . "GPA," . "Proj Pref 1," . "Proj Just 1," . "Proj Pref 2," . "Proj Just 2," . "Proj Pref 3," . "Proj Just 3," . "Proj Pref 4," . "Proj Just 4," . "Proj Pref 5," . "Proj Just 5," . "Pref Stud 1," . "Pref Stud 2, " . "Pref Stud 3." . "Pref Stud 4," . "Pref Stud 5," . "Not Pref 1," . "Not Pref 2," . "Not Pref 3," . "Not Pref 4," . "Not Pref 5," . "\n";
      
	    foreach($rows as $r) {
			$info .=  $r['snum'] . ",";
		  $info .=  $r['first_name'] . ",";
			$info .=  $r['last_name'] . ",";
			$info .=  $r['pass_fail_pm'] . ",";
			$info .=  $r['degree_ids'] . ",";
		  $info .=  $r['major_ids'] . ",";
			$info .=  $r['skill_set_ids'] . ",";
			$info .=  $r['gpa'] . ",";
			$info .=  $r['proj_pref1'] . ",";
			$info .=  $r['proj_just1'] . ",";
			$info .=  $r['proj_pref2'] . ",";
			$info .=  $r['proj_just2'] . ",";
			$info .=  $r['proj_pref3'] . ",";
			$info .=  $r['proj_just3'] . ",";
			$info .=  $r['proj_pref4'] . ",";
			$info .=  $r['proj_just4'] . ",";
			$info .=  $r['proj_pref5'] . ",";
			$info .=  $r['proj_just5'] . ",";
			$info .=  $r['y_stu_pref1'] . ",";
			$info .=  $r['y_stu_pref2'] . ",";
			$info .=  $r['y_stu_pref3'] . ",";
			$info .=  $r['y_stu_pref4'] . ",";
			$info .=  $r['y_stu_pref5'] . ",";
			$info .=  $r['n_stu_pref1'] . ",";
			$info .=  $r['n_stu_pref2'] . ",";
			$info .=  $r['n_stu_pref3'] . ",";
			$info .=  $r['n_stu_pref4'] . ",";
			$info .=  $r['n_stu_pref5'] . ", \n";
		}
	}
  //Not sure if this is right
	else if($opt=='groups')
	{
		$rows = Doctrine_Core::getTable('ProjectAllocation')->findAll();
      $info .= "Group ID," . "Proj ID," . "Stud No's," . "\n";
	    foreach($rows as $r) {
			$info .=  $r['id'] . ",";
		  $info .=  $r['project_id'] . ",";
			$info .=  $r['snum'] . ", \n";
		}
	}
	// This tells it to use the csv.php template file
	// note- all content is still contained within $sf_content
	// see templates/csv.php to see how to treat $sf_content
    $this->setlayout('csv');
	
	// print out the results to this new page layout
	echo $info;
	
	
	// set up the httpHeaders to properly treat the page
    $this->getResponse()->clearHttpHeaders();
    $this->getResponse()->setHttpHeader('Content-Type', 'application/vnd.ms-excel');
    $this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename=PANS_'.$opt.'List_'.date("Ymd").'.csv');

    // Redirecting seems to break the download.  In this case, probably no 
    // flash is better.
    // A redirect is needed to show the flash
	//  $this->getUser()->setFlash('notice', 'Projects exported.');
	// $this->redirect('project/tool');
  }

  // Change the deadline of the nomination round
  public function executeChangeDeadline(sfWebRequest $request)
  {
    try {
      // PHP Error handling is really, really horrible
      $deadline = DateTime::createFromFormat('Y-m-d', $request->getPostParameter('deadline'));
      if ($deadline == false)
        throw new Exception();
    } catch (Exception $e) {
      $this->getUser()->setFlash('error', 'Invalid date. Please use YYYY-MM-DD.');
      $this->redirect('project/tool');
    }

    $conn = Doctrine_Manager::getInstance();
    $round = Doctrine_Core::getTable('NominationRound')->findAll();
    try {
      $round->delete();
    } catch (Exception $e) {}
    
    $round = new NominationRound();
    $round->setDeadline($deadline->format('Y-m-d'));
    $round->save();

    $this->getUser()->setFlash('notice', 'New date applied.');
    $this->redirect('project/tool');
  }

  // Manually handling the file upload and parsing
  // We have two tables, one for student forms and one for login. 
  // We have to create a record in both tables.
  public function executeImportStudents(sfWebRequest $request)
  {
    // Ensure the file is uploaded okay
    if ($_FILES['studentFile']['error'] !== UPLOAD_ERR_OK) {
      $this->getUser()->setFlash('error', 
            $this->file_upload_error_message($_FILES['studentFile']['error']));
      $this->redirect('project/tool');
    }
    
    // Reads the entire content
    $raw_data = file_get_contents($_FILES['studentFile']['tmp_name']);
    $raw_data = explode("\n", $raw_data); 
    // Delete the header
    $raw_data = array_slice($raw_data, 1);
    
    // Assume index 2 is ID, and index 3 is name. Store them as such.
    $students = array();
    $i = 0;
    foreach ($raw_data as $line) {
      $items = str_getcsv($line);
      if (count($items) < 2)
        continue;
      $id = $items[2];
      $name = explode(",", $items[3]);
      $firstName = $name[1];
      $lastName = $name[0];
      $students[$i] = array('snum' => $id, 
                            'first_name' => $firstName, 
                            'last_name' => $lastName);
      $i++;
    }
    
    // Get database connection
    $conn = Doctrine_Manager::getInstance();
    $student_user = Doctrine_Core::getTable('StudentUser');
    $guard_user = Doctrine_Core::getTable('sfGuardUser');
    $this->student_user_collection = new Doctrine_Collection('StudentUser');
    
    // Add students
    foreach ($students as $student) {
      $user = new StudentUser();
      $user->snum = $student['snum'];
      $user->first_name = $student['first_name'];
      $user->last_name = $student['last_name'];
      $this->student_user_collection->add($user);
    }
    
    // Commit the new students into database
    try {
      $this->student_user_collection->save();
    } catch (Doctrine_Connection_Mysql_Exception $e) {
      $this->getUser()->setFlash('error', 'Failed to import students: ' . $e->getMessage());
      $this->redirect('project/tool');
    }

    // "The task is done, m'lord."
    $this->getUser()->setFlash('notice', 'Students imported successfully.  Please email their passwords.');
    $this->redirect('project/tool');
  }

  ////////////////////////////////////////////////////////////////////////
  // Delete-related functions
  
  // Delete all projects in the database
  public function executeClearAllProjects(sfWebRequest $request)
  {
    $conn = Doctrine_Manager::getInstance();
    $projects = Doctrine_Core::getTable('Project')->findAll();
    $projects->delete();
    
    $this->getUser()->setFlash('notice', 'Projects deleted.');
    $this->redirect('project/tool');
  }
    
  // Delete all students and their login details in the database
  public function executeClearAllStudents(sfWebRequest $request)
  {
    $conn = Doctrine_Manager::getInstance();
    $students = Doctrine_Core::getTable('StudentUser')->findAll();
    $students->delete();
    
    $this->getUser()->setFlash('notice', 'Students deleted.');
    $this->redirect('project/tool');
  }
 
  ////////////////////////////////////////////////////////////////////////
  // Helpler functions
  
  // Resets one student's password and emails him/her the new password
  // Deletes the old password.  Unfortunately even we don't know what your old password was.
  protected function emailPassword($snum, $first_name, $last_name)
  {
    $conn = Doctrine_Manager::getInstance();
    $guard_user = Doctrine_Core::getTable('sfGuardUser')->findOneBy('username', array($snum));
    
    if ($guard_user == null)
      $guard_user = new sfGuardUser();

    $password = $this->random_password();
      
    $guard_user->setEmailAddress('s' . $snum . '@griffithuni.edu.au');
    $guard_user->setUsername($snum);
    $guard_user->setPassword($password);
    $guard_user->setFirstName($first_name);
    $guard_user->setLastName($last_name);
    $guard_user->setIsActive(true);
    $guard_user->setIsSuperAdmin(false);
    
    $guard_user->save();

    $message = "Dear " . $guard_user->getFirstName() . "," . PHP_EOL . PHP_EOL . 
               "Your account has been created for the project allocation and nomination system." . PHP_EOL . PHP_EOL .
               "Username: " . $snum . PHP_EOL .
               "Password: " . $password . PHP_EOL . PHP_EOL  .
               "Please follow the links to fill in your project nomination form:" . PHP_EOL .
               "http://" . $this->getRequest()->getHost() . PHP_EOL . PHP_EOL .
               "Thanks,\nProject Allocation and Nomination System (PANS)";

    $headers = 'From: "PANS" <' . $this->getUser()->getGuardUser()->getEmailAddress() . '>' . PHP_EOL . 'X-Mailer: PHP-' . phpversion() . PHP_EOL;
    
    $result = mail( $guard_user->getEmailAddress(),
                    "3001ICT - Your password has been created for project nominations",
                    $message,
                    $headers);
        
    if ($result === false) {
      $this->getUser()->setFlash('notice', 'Password reset.  Email failed to send.');
    } else {
      $this->getUser()->setFlash('notice', 'Password reset.  Email sent.');
    }
  }
  
  
  // Mass email every student their resetted passwords
  public function executeEmailAllPasswords()
  {
  // TODO: Actually email everyone not just me.
    $this->emailPassword(2674674, 'Xavier', 'Ho');

    $this->redirect('project/tool');
  }

  // File error handling from
  // http://www.php.net/manual/en/features.file-upload.errors.php
  protected function file_upload_error_message($error_code) 
  {
    switch ($error_code) { 
      case UPLOAD_ERR_INI_SIZE: 
        return 'The uploaded file exceeds the upload_max_filesize directive in php.ini'; 
      case UPLOAD_ERR_FORM_SIZE: 
        return 'The uploaded file exceeds the maximum file size (MAX_FILE_SIZE).'; 
      case UPLOAD_ERR_PARTIAL: 
        return 'The uploaded file was only partially uploaded'; 
      case UPLOAD_ERR_NO_FILE: 
        return 'No file was uploaded'; 
      case UPLOAD_ERR_NO_TMP_DIR: 
        return 'Missing a temporary folder'; 
      case UPLOAD_ERR_CANT_WRITE: 
        return 'Failed to write file to disk'; 
      case UPLOAD_ERR_EXTENSION: 
        return 'File upload stopped by extension'; 
      default: 
        return 'Unknown upload error'; 
    } 
  } 
  
  // Random password generation, good for one-time login
  // http://www.lost-in-code.com/programming/php-code/php-random-string-with-numbers-and-letters/
  protected function random_password() 
  {
    $length = 8;
    // 0 and O, l and 1 are all removed to prevent silly people who can't read to make mistakes.
    $characters = '23456789abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
    $string = '';    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
  }
}
