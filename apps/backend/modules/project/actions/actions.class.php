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
  
  

  /*------------------------------------------------------------------
    Export function using the fputcsv approach
  */
   public function executeExportTables(sfWebRequest $request)
  {  

  
    // Setup the connection
    $conn = Doctrine_Manager::getInstance();
  
    // Setup a variable to catch our data to export
    $info;
    $rows;


    // Then we grab the value of the drop down box
    $opt = $request->getPostParameter('infoType');
    
    if($opt=='students')
    {
      //ask symfony to return the data from one of our tables
      $rows = Doctrine_Core::getTable('StudentUser')->findAll();
      $info = array(
        'S Number', //any changes to this first cell MUST be reflected in importStudents()
        'First Name', 
        'Last Name', 
        'Pass/Fail PM',
        'Degree',
        'Major',
        'Skills',
        'GPA',
        'Proj Pref 1',
        'Proj Pref 2',
        'Proj Pref 3',
        'Proj Pref 4',
        'Proj Pref 5',
        'Desired Snum 1',
        'Desired Snum 2',
        'Desired Snum 3',
        'Desired Snum 4',
        'Desired Snum 5',
        'Undesired Snum 1',
        'Undesired Snum 2',
        'Undesired Snum 3',
        'Undesired Snum 4',
        'Undesired Snum 5',
        'Proj Just 1',
        'Proj Just 2',
        'Proj Just 3',
        'Proj Just 4',
        'Proj Just 5',
        'Form Completed',
        'Flags',
        'created_at',
        'updated_at');
    }
    elseif($opt=='projects')
    {
      //ask symfony to return the data from one of our tables
      $rows = Doctrine_Core::getTable('Project')->findAll();
      $info = array('Project id#', 'Title', 'Organisation', 'Description', 'More Info?', 'GPA Cutoff', 'Major ID\'s', 'Skill Set ID\'s');
    }
    elseif($opt=='groups')
    {
      //ask symfony to return the data from one of our tables
      $rows = Doctrine_Core::getTable('ProjectAllocation')->findAll();
      $info = array('Group ID','Proj ID','S Number');
    }

    // Setup the file pointer
    $fpath = 'downloads/PANS_'.$opt.'List_'.date("Y-m-d").'.csv';
    
    //we first check if the file exists. If it does we move on, if not...
    if(!file_exists(dirname($fpath)))
    {
      //we then make it and check if this was successful, if not...
      if(!mkdir(dirname($fpath)))
      { 
        //we provide feedback and redirect the user
        $this->getUser()->setFlash('error', 'Could not create directory. Please ensure you have folder privilages for "'.dirname($fpath).'"');
        $this->redirect('project/tool');
      }
    }

    //open the file
    $fp = fopen($fpath, 'w+');//or 'c'?

    //we then check if the fopen was successful, if not...
    if(!$fp) 
    {
      //we provide feedback and redirect the user
      $this->getUser()->setFlash('error', 'Could not open file. Please ensure that if the file already exists it is not in use.');
      $this->redirect('project/tool');
    }
    
    //so if we got through with no errors

    //we write the first row using the headers
    fputcsv($fp, $info);

    //create an array to better work with symfony's data array return
    $data = array();

    //so iterate through and convert to normal array
    foreach($rows as $r)
    {
     $x = -1;
      foreach($r as $v)
      {
        $data[$x++] = $v;//adds value and increments $x
      }
      //this method parses the array and treats all special char
      fputcsv($fp, $data);
    }

    //close the file as we are done now
    fclose($fp);

    //notify the user of the status and location of the file
    $this->getUser()->setFlash('notice', 'File successfully saved to "'. $fpath .'"');
    //redirect to the tool page
    $this->redirect('project/tool');
}










 /* ------------------------------------------------------------------
   Manually handling the file upload and parsing
   We have two tables, one for student forms and one for login. 
   We have to create a record in both tables.
    
   This also takes as an input two file content layouts:
     1. The layout generated by learning@griffith
     2. The layout generated by PANS exportTables()
      
   The main difference is the useful extra information contained within the PANS version, 
    and the useless extra information in the learning@griffith version
  */
  public function executeImportStudents(sfWebRequest $request)
  {
    //sfLogger::debug($request);
    
    // Ensure the file is uploaded okay
    if ($_FILES['studentFile']['error'] !== UPLOAD_ERR_OK) {
      $this->getUser()->setFlash('error', 
            $this->file_upload_error_message($_FILES['studentFile']['error']));
      $this->redirect('project/tool');
    }
    // Reads the entire content
    $raw_data = file_get_contents($_FILES['studentFile']['tmp_name']);
    $raw_data = explode("\n", $raw_data); 
   
    // Grab the header and remove it from $raw_data
    $firstLine = array_shift($raw_data);

    //setup some variables to help capture the data read
    $students = array();
    $i = 0;


  //now check the header to determine how to handle

  // FIXME: This is really hacky. I'm not a fan but whatever.
  // We should employ error checking and not ... checking for an unused field.
  if(strcasecmp($firstLine[0],'N')==0)//this is always the first cell generated by learning@griffith
  {
    //LEARNING@GRIFFITH LAYOUT
    // Assume index 2 is ID, and index 3 is name. Store them as such.
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
  }
  elseif(strcasecmp($firstLine[1],'S')==0)//this must match with the first cell as set in exportTables()
  {  
    //PANS LAYOUT
    // Assume index 0 is ID, and each field follows as in the database. Store them as such.
    foreach ($raw_data as $line) 
    {
        $items = str_getcsv($line);
        if (count($items) < 2)
          continue;
     $x = 0;
     $students[$i] = array(
        'snum'           =>     $items[$x++],
        'first_name'     =>     $items[$x++],
        'last_name'      =>     $items[$x++],
        'pass_fail_pm'   =>     $items[$x++],
        'major_ids'      =>     $items[$x++],
        'degree_ids'     =>     $items[$x++],
        'gpa'            =>     $items[$x++],
        'proj_pref1'     =>     $items[$x++],
        'proj_pref2'     =>     $items[$x++],
        'proj_pref3'     =>     $items[$x++],
        'proj_pref4'     =>     $items[$x++],
        'proj_pref5'     =>     $items[$x++],
        'skill_set_ids'  =>     $items[$x++],
        'y_stu_pref1'    =>     $items[$x++],
        'y_stu_pref2'    =>     $items[$x++],
        'y_stu_pref3'    =>     $items[$x++],
        'y_stu_pref4'    =>     $items[$x++],
        'y_stu_pref5'    =>     $items[$x++],
        'n_stu_pref1'    =>     $items[$x++],
        'n_stu_pref2'    =>     $items[$x++],
        'n_stu_pref3'    =>     $items[$x++],
        'n_stu_pref4'    =>     $items[$x++],
        'n_stu_pref5'    =>     $items[$x++],
        'proj_just1'     =>     $items[$x++],
        'proj_just2'     =>     $items[$x++],
        'proj_just3'     =>     $items[$x++],
        'proj_just4'     =>     $items[$x++],
        'proj_just5'     =>     $items[$x++],
        'form_completed' =>     $items[$x++],
        'created_at'     =>     $items[$x++],
        'updated_at'     =>     $items[$x++]
        );
        $i++;
    }

  }
    
    
    // Get database connection
    $conn = Doctrine_Manager::getInstance();
    $student_user = Doctrine_Core::getTable('StudentUser');
    $guard_user = Doctrine_Core::getTable('sfGuardUser');
    $this->student_user_collection = new Doctrine_Collection('StudentUser');
    
    // Add students
    foreach ($students as $student) 
    {
      $user = new StudentUser();
      $user->snum = $student['snum'];
      $user->first_name = $student['first_name'];
      $user->last_name = $student['last_name'];

      // FIXME: Hackiness continued
      if(strcasecmp($firstLine[1],'S')==0)
      {
        $user->pass_fail_pm= $student['pass_fail_pm'];
        $user->major_ids= $student['major_ids'];
        $user->degree_ids= $student['degree_ids'];
        $user->gpa= $student['gpa'];
        $user->proj_pref1= $student['proj_pref1'];
        $user->proj_pref2= $student['proj_pref2'];
        $user->proj_pref3= $student['proj_pref3'];
        $user->proj_pref4= $student['proj_pref4'];
        $user->proj_pref5= $student['proj_pref5'];
        $user->skill_set_ids= $student['skill_set_ids'];   
        $user->y_stu_pref1= $student['y_stu_pref1'];
        $user->y_stu_pref2= $student['y_stu_pref2'];
        $user->y_stu_pref3= $student['y_stu_pref3'];
        $user->y_stu_pref4= $student['y_stu_pref4'];
        $user->y_stu_pref5= $student['y_stu_pref5'];
        $user->n_stu_pref1= $student['n_stu_pref1'];
        $user->n_stu_pref2= $student['n_stu_pref2'];
        $user->n_stu_pref3= $student['n_stu_pref3'];
        $user->n_stu_pref4= $student['n_stu_pref4'];
        $user->n_stu_pref5= $student['n_stu_pref5'];
        $user->proj_just1= $student['proj_just1'] ;
        $user->proj_just2= $student['proj_just2'] ;
        $user->proj_just3= $student['proj_just3'] ;
        $user->proj_just4= $student['proj_just4'] ;
        $user->proj_just5= $student['proj_just5'] ;
        $user->form_completed= $student['form_completed'];
        $user->created_at= $student['created_at'];
        $user->updated_at= $student['updated_at'];
      }
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
    $this->getUser()->setFlash('notice', 'Students imported successfully.');

    //for now just a placeholder - should use the return from the email function

    //                    ^
    // Xav: I'm against this.  LE might want to keep the database at initia state without 
    // letting students know.  Unless you get her agreement on this, I will be against this idea.

    $this->redirect('project/tool');
    echo 'done';
  }





  ////////////////////////////////////////////////////////////////////////
  // Delete-related functions
  
  /*------------------------------------------------------------------
      Delete all projects in the database
  */
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
    // Delete all groups after they have been sorted, justin did this yayy!!
   public function executeClearAllGroups(sfWebRequest $request)
  {
    $conn = Doctrine_Manager::getInstance();
    $groups = Doctrine_Core::getTable('ProjectAllocation')->findAll();
    $groups->delete();
    
    $this->getUser()->setFlash('notice', 'Groups deleted.');
    $this->redirect('project/tool');
  }
  ////////////////////////////////////////////////////////////////////////
  // Helpler functions
  
  /*------------------------------------------------------------------
    Resets one student's password and emails him/her the new password
    Deletes the old password.  Unfortunately even we don't know what your old password was.
  */
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
  
  
  /*------------------------------------------------------------------
   Mass email every student their resetted passwords
  */
  public function executeEmailAllPasswords()
  {
  // TODO: Actually email everyone not just me.
    $this->emailPassword(2674674, 'Xavier', 'Ho');

    $this->redirect('project/tool');
  }

  /*------------------------------------------------------------------
   File error handling from
   http://www.php.net/manual/en/features.file-upload.errors.php
  */
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


