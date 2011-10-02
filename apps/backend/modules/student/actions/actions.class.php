<?php

require_once dirname(__FILE__).'/../lib/studentGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/studentGeneratorHelper.class.php';

/**
 * student actions.
 *
 * @package    PANS
 * @subpackage student
 * @author     Pinpoint Solutions
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class studentActions extends autoStudentActions
{
  // Import Students From File in disguise
  // Do not rename, remove, or modify this method
  public function executeShow(sfWebRequest $request)
  {
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
    $this->guard_user_collection = new Doctrine_Collection('sfGuardUser');
    
    // Add students
    // TODO: Randomly generate a password for them too
    foreach ($students as $student) {
      $user = new StudentUser();
      $user->snum = $student['snum'];
      $user->first_name = $student['first_name'];
      $user->last_name = $student['last_name'];
      
      $auth_user = new sfGuardUser();
      $auth_user->setEmailAddress('s' . $student['snum'] . '@griffithuni.edu.au');
      $auth_user->setUsername($student['snum']);
      $auth_user->setPassword($this->random_password());
      $auth_user->setFirstName($student['first_name']);
      $auth_user->setLastName($student['last_name']);
      $auth_user->setIsActive(true);
      $auth_user->setIsSuperAdmin(false);
      
      $this->student_user_collection->add($user);
      $this->guard_user_collection->add($auth_user);
    }
    
    // Commit the new students into database
    try {
      $this->student_user_collection->save();
      $this->guard_user_collection->save();
    } catch (Doctrine_Connection_Mysql_Exception $e) {
      $this->getUser()->setFlash('error', 'Failed to import students: ' . $e->getMessage());
      $this->redirect('project/tool');
    }

    // "The task is done, m'lord."
    $this->getUser()->setFlash('notice', 'Students imported successfully.  Please email their passwords.');
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
    $length = 16;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()[]{}<>?/\\';
    $string = '';    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
  }
}
