<?php

require_once dirname(__FILE__).'/../lib/projectGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/projectGeneratorHelper.class.php';

/**
 * project actions.
 *
 * @package    PANS
 * @subpackage project
 * @author     Pinpoint Solutions
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class projectActions extends autoProjectActions
{
  // Admin tools
  // Do not modify or remove this function
  public function executeTool(sfWebRequest $request)
  {
    $this->email = $this->getUser()->getGuardUser()->getEmailAddress();
    $this->deadline = Doctrine_Core::getTable('NominationRound')
      ->createQuery('a')
      ->fetchOne()
      ->getDeadline();
  }
  
  // Admin View for the Group Page
  public function executeGroup(sfWebRequest $request)
  {
    $this->groups = Doctrine_Core::getTable('ProjectAllocation')
      ->createQuery('a')
      ->execute();
  }
  
  public function executeExportProjects(sfWebRequest $request)
  {
    // We need some code here that calls information from the database.
    // get connection
    // go to projects table
    // then 'recall' information or some such thing.
    $conn = Doctrine_Manager::getInstance();
    $projects = Doctrine_Core::getTable('Project')->findAll();
   
    foreach($projects as $r) {
      //update formatting to be easier to treat, for example escapeSlashes to stop injection
      echo $r['id'] . "\n";
      echo $r['title'] . "\n";
      echo $r['organisation'] . "\n";
      echo $r['description'] . "\n";
      echo $r['has_additional_info'] . "\n";
      echo $r['has_gpa_cutoff'] . "\n";
      echo $r['major_ids'] . "\n";
      echo $r['skill_set_ids'] . "\n \n";	 	 	 	
    }

  	$this->setlayout('csv');

  	$this->getResponse()->clearHttpHeaders();
  	$this->getResponse()->setHttpHeader('Content-Type', 'application/vnd.ms-excel');
    //maybe add timestamp to the filename
  	$this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename=PANS_projectList.csv');

    $this->getUser()->setFlash('notice', 'Projects exported.');
  }
  
  // The _real_ import students from file action
  // Manually handling the file upload and parsing
  // We have two tables, one for student forms and one for login. We have to
  // create a record in both tables.
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
               "Please follow the links to fill in your project nomination form." . PHP_EOL . PHP_EOL .
               "Auto-generated-message-sincerely-yours,\nProject Allocation and Nomination System (PANS)";
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
  
  // Delete all students and their login details in the database
  public function executeClearAllStudents(sfWebRequest $request)
  {
    $conn = Doctrine_Manager::getInstance();
    $students = Doctrine_Core::getTable('StudentUser')->findAll();
    $students->delete();
    
    $this->getUser()->setFlash('notice', 'Students deleted.');
    $this->redirect('project/tool');
  }
  
  // Delete all projects in the database
  public function executeClearAllProjects(sfWebRequest $request)
  {
    $conn = Doctrine_Manager::getInstance();
    $projects = Doctrine_Core::getTable('Project')->findAll();
    $projects->delete();
    
    $this->getUser()->setFlash('notice', 'Projects deleted.');
    $this->redirect('project/tool');
  }
  
  // Change the deadline of the nomination round
  public function executeChangeDeadline(sfWebRequest $request)
  {
    /*
    $conn = Doctrine_Manager::getInstance();
    $students = Doctrine_Core::getTable('NominationRound')->findAll();
    $students->delete();
    */
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
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()[]{}<>?/\\';
    $string = '';    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
  }
}
