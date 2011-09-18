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
  // Import Student From File in disguise
  // Do not rename, remove, or modify this method
  public function executeShow(sfWebRequest $request)
  {
  }
  
  // Manually handling the file upload and parsing
  public function executeImportStudents(sfWebRequest $request)
  {
    // Ensure the file is uploaded okay
    if ($_FILES['studentFile']['error'] !== UPLOAD_ERR_OK) {
      $this->getUser()->setFlash('error', 
            $this->file_upload_error_message($_FILES['studentFile']['error']));
      $this->redirect('student/index');
    }
    
    // Reads the entire content
    $raw_data = file_get_contents($_FILES['studentFile']['tmp_name']);
    $raw_data = explode("\n", $raw_data); 
    // Delete the header
    unset($raw_data[0]);
    
    // Assume index 2 is ID, and index 3 is name. Store them as such.
    $students = array();
    $i = 0;
    foreach ($raw_data as $line) {
      $items = str_getcsv($line);
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
    $this->collection = new Doctrine_Collection('StudentUser');
    
    // Add students
    foreach ($students as $student) {
      $user = new StudentUser();
      $user->snum = $student['snum'];
      $user->first_name = $student['first_name'];
      $user->last_name = $student['last_name'];
      $this->collection->add($user);
    }
    $this->collection->save();
    $this->msg = $this->collection;

    $this->getUser()->setFlash('notice', 'Students imported successfully.');
    $this->redirect('student/index');
  }
  
  // File error handling from
  // http://www.php.net/manual/en/features.file-upload.errors.php
  protected function file_upload_error_message($error_code) {
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
}
