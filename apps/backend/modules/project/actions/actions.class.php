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
    $opt = $request->getPostParameter('infoType');

    // Setup the file pointer. Notice no / at the start, this is needed for the link but has caused issues when added before the fopen was called
    $fpath = 'downloads/PANS_' . $opt . '_List_' . date("Y-m-d") . '.csv';
    mkdir(dirname($fpath));

    // open the file
    $fp = fopen($fpath, 'w');
    if(!$fp) {
      $this->getUser()->setFlash('error', 'Could not create file "'.$fpath.'". Please ensure that if the file already exists it is not in use.');
      $this->redirect('project/tool');
    } 

    if (strcasecmp($opt, 'students') == 0) {
      $rows = Doctrine_Core::getTable('StudentUser')->findAll();
      $fields = array(
        'ID', //any changes to this first cell MUST be reflected in importStudents()
        'Name', 
        'Pass/Fail PM',
        'Degree IDs',
        'Major IDs',
        'Skill IDs',
        'GPA',
        '1st Project Preference',
        '1st Project Justification',
        '2nd Project Preference',
        '2nd Project Justification',
        '3rd Project Preference',
        '3rd Project Justification',
        '4th Project Preference',
        '4th Project Justification',
        '5th Project Preference',
        '5th Project Justification',
        'Desired Student 1',
        'Desired Student 2',
        'Desired Student 3',
        'Desired Student 4',
        'Desired Student 5',
        'Undesired Student 1',
        'Undesired Student 2',
        'Undesired Student 3',
        'Undesired Student 4',
        'Undesired Student 5',
        'Form Completed',
        'Flag');
      fputcsv($fp, $fields);
      foreach($rows as $r) {
        $data = array(  // Order is very important here. Do not change them unless you also change the fields order above
          $r->getSnum(),
          $r->getFirstName() . ' ' . $r->getLastName(),
          $r->getPassFailPm(),
          $r->getDegreeIds(),
          $r->getMajorIds(),
          $r->getSkillSetIds(),
          $r->getGpa(),
          $r->getProjPref1(),
          $r->getProjJust1(),
          $r->getProjPref2(),
          $r->getProjJust2(),
          $r->getProjPref3(),
          $r->getProjJust3(),
          $r->getProjPref4(),
          $r->getProjJust4(),
          $r->getProjPref5(),
          $r->getProjJust5(),
          $r->getYStuPref1(),
          $r->getYStuPref2(),
          $r->getYStuPref3(),
          $r->getYStuPref4(),
          $r->getYStuPref5(),
          $r->getFormCompleted(),
          $r->getFlag(),
          );
        fputcsv($fp, $data);
      }
    }
    elseif (strcasecmp($opt, 'projects') == 0) {
      $rows = Doctrine_Core::getTable('Project')->findAll();
      $fields = array('Title', 'Organisation', 'Description', 'Extended Description', 'Has More Info', 'Has GPA Cutoff', 'Max Group Size', 'Degree IDs', 'Major IDs', 'Skill IDs');
      fputcsv($fp, $fields);
      foreach($rows as $r) {
        $data = array(  // Order is very important here. Do not change them unless you also change the fields order above
          $r->getTitle(),
          $r->getOrganisation(),
          preg_replace("\n", "   ", $r->getDescription()),
          preg_replace("\n", "   ", $r->getExtendedDescription()),
          $r->getHasAdditionalInfo(),
          $r->getHasGpaCutoff(),
          $r->getMaxGroupSize(),
          $r->getDegreeIds(),
          $r->getMajorIds(),
          $r->getSkillSetIds()
        );
        fputcsv($fp, $data);
      }
    }
    elseif (strcasecmp($opt, 'groups') == 0) {
      $rows = Doctrine_Core::getTable('ProjectAllocation')->findAll();
      $fields = array('Project ID','Student Number');
      fputcsv($fp, $fields);
      foreach($rows as $r) {
        $data = array(  // Order is very important here. Do not change them unless you also change the fields order above
          $r->getProjectId(),
          $r->getSnum()
        );
        fputcsv($fp, $data);
      }
    }

    // close the file as we are done now
    if(!fclose($fp))
    {
      $this->getUser()->setFlash('error', 'Operation not successful. File "'.$fpath.'" not created.');
      $this->redirect('project/tool');
    }
    else 
    {
      // notify the user of the status and location of the file
      $this->getUser()->setFlash('notice', 'Successfully saved.  <a href="http://' .$this->getRequest()->getHost() .'/'.  $fpath .'"> Click to download.</a>');
      $this->redirect('project/tool');
    }
  }


  /*------------------------------------------------------------------
    adds individual student
  */
  public function executeAddStudent(sfWebRequest $request)
  {
      $formData =  $request->getPostParameters();
      
      // Get database connection
      $conn = Doctrine_Manager::getInstance();
      $this->guard_user_collection = new Doctrine_Collection('sfGuardUser');
      $this->student_user_collection = new Doctrine_Collection('StudentUser');
    
      $guard_user = new sfGuardUser();
      $password = $this->random_password();
      
      $guard_user->setEmailAddress('s' . $formData['snum'] . '@griffithuni.edu.au');//FIXME
      $guard_user->setUsername($formData['snum']);
      $guard_user->setPassword($password); 
      $guard_user->setFirstName($formData['fName']);
      $guard_user->setLastName($formData['lName']);
      $guard_user->setIsActive(true);
      $guard_user->setIsSuperAdmin(false);
      $this->guard_user_collection->add($guard_user);

      $user = new StudentUser();
      $user->snum = $formData['snum'];
      $user->first_name = $formData['fName'];
      $user->last_name = $formData['lName'];
      $this->student_user_collection->add($user);
      
      // Commit the new student into database
      try {
        $this->student_user_collection->save();
        $this->guard_user_collection->save();
      } catch (Doctrine_Connection_Mysql_Exception $e) {
        $this->getUser()->setFlash('error', 'Failed to import students.  Please check for duplicated entries and try again.  Message: ' . $e->getMessage());
        $this->redirect('project/tool');
      }

      //notice including the added student number
      $this->getUser()->setFlash('notice', 'Student "'.$formData['snum'].'" added successfully.');
      $this->redirect('project/tool');
    }



 /* ------------------------------------------------------------------
   Manually handling the file upload and parsing
   We have two tables, one for student forms and one for login. 
   We have to create a record in both tables.
    
   This function looks for names in the first header row and decides
   which column to import.
  */
  public function executeImportStudents(sfWebRequest $request)
  {
    // Get database connection
    $conn = Doctrine_Manager::getInstance();
    $this->guard_user_collection = new Doctrine_Collection('sfGuardUser');
    $this->student_user_collection = new Doctrine_Collection('StudentUser');

    // Ensure the file is uploaded okay
    if ($_FILES['studentFile']['error'] !== UPLOAD_ERR_OK) {
      $this->getUser()->setFlash('error', 
            $this->file_upload_error_message($_FILES['studentFile']['error']));
      $this->redirect('project/tool');
    }
    // Read the entire content
    $raw_data = file_get_contents($_FILES['studentFile']['tmp_name']);
    $raw_data = explode("\n", $raw_data); 
   
    // Grab the header and remove it from $raw_data
    $firstLine = array_shift($raw_data);
    $firstLine = explode(",", $firstLine);

    // Look for important fields in header to import
    $columns = array();
    $fields = array(
        'ID', 
        'Name',       
        'Pass/Fail PM',
        'Degree IDs',
        'Major IDs',
        'Skill IDs',
        'GPA',
        '1st Project Preference',
        '1st Project Justification',
        '2nd Project Preference',
        '2nd Project Justification',
        '3rd Project Preference',
        '3rd Project Justification',
        '4th Project Preference',
        '4th Project Justification',
        '5th Project Preference',
        '5th Project Justification',
        'Desired Student 1',
        'Desired Student 2',
        'Desired Student 3',
        'Desired Student 4',
        'Desired Student 5',
        'Undesired Student 1',
        'Undesired Student 2',
        'Undesired Student 3',
        'Undesired Student 4',
        'Undesired Student 5',
        'Form Completed',
        'Flag');
    foreach ($firstLine as $i => $column) {
      if (in_array($column, $fields))
        $columns[$column] = $i;
      else
        $columns[$column] = -1;
    }
    
    // Add students
    foreach ($raw_data as $student) {
      $data = str_getcsv($student);
      
      $user = new StudentUser();
      $user->setSnum($data[$columns['ID']]);

      // If the name contains a comma, last name comes first
      $name = $data[$columns['Name']];
      if (strpos($name, ",") !== false) {
        $n = explode(",", $name);
        $user->setLastName($n[0]);
        $user->setFirstName($n[1]);
      } else {
        $n = explode(" ", $name);
        $user->setFirstName(array_shift($n));
        $user->setLastName(implode(" ", $n));
      }

      // At this point, we have the initial data.  The rest is not guaranteed,
      // but if there are more information we exported, we will import it.
      try {
        $user->setPassFailPm($data[$columns['Pass/Fail PM']]);
        $user->setDegreeIds($data[$columns['Degree IDs']]);
        $user->setMajorIds($data[$columns['Major IDs']]);
        $user->setSkillSetIds($data[$columns['Skill IDs']]);
        $user->setGPA($data[$columns['GPA']]);
        $user->setProjPref1($data[$columns['1st Project Preference']]);
        $user->setProjPref2($data[$columns['2nd Project Preference']]);
        $user->setProjPref3($data[$columns['3rd Project Preference']]);
        $user->setProjPref4($data[$columns['4th Project Preference']]);
        $user->setProjPref5($data[$columns['5th Project Preference']]);
        $user->setYStuPref1($data[$columns['Desired Student 1']]);
        $user->setYStuPref2($data[$columns['Desired Student 2']]);
        $user->setYStuPref3($data[$columns['Desired Student 3']]);
        $user->setYStuPref4($data[$columns['Desired Student 4']]);
        $user->setYStuPref5($data[$columns['Desired Student 5']]);
        $user->setNStuPref1($data[$columns['Undesired Student 1']]);
        $user->setNStuPref2($data[$columns['Undesired Student 2']]);
        $user->setNStuPref3($data[$columns['Undesired Student 3']]);
        $user->setNStuPref4($data[$columns['Undesired Student 4']]);
        $user->setNStuPref5($data[$columns['Undesired Student 5']]);
        $user->setProjJust1($data[$columns['1st Project Justification']]);
        $user->setProjJust2($data[$columns['2nd Project Justification']]);
        $user->setProjJust3($data[$columns['3rd Project Justification']]);
        $user->setProjJust4($data[$columns['4th Project Justification']]);
        $user->setProjJust5($data[$columns['5th Project Justification']]);
        $user->setFormCompleted($data[$columns['Form Completed']]);
        $user->setFlag($data[$columns['Flag']]);
      } catch (Exception $e) {
        // Well, too bad. You get what you get.
      }
      if ($user->getSnum() != 0)
        $this->student_user_collection->add($user);

      // FIXME - allow more than just griffith default domain emails
      $guard_user = new sfGuardUser();
      $password = $this->random_password();
      $guard_user->setEmailAddress('s' . $user->getSnum() . '@griffithuni.edu.au'); 
      $guard_user->setUsername($user->getSnum());
      $guard_user->setPassword($password); 
      $guard_user->setFirstName($user->getFirstName());
      $guard_user->setLastName($user->getLastName());
      $guard_user->setIsActive(true);
      $guard_user->setIsSuperAdmin(false);
      $this->guard_user_collection->add($guard_user);
    }

    // Commit the new students into database
    try {
      $this->student_user_collection->save();
      $this->guard_user_collection->save();
    } catch (Doctrine_Connection_Mysql_Exception $e) {
      $this->getUser()->setFlash('error', 'Failed to import students.  Please check for duplicated entries and try again.  Message: ' . $e->getMessage());
      $this->redirect('project/tool');
    }

    // "The task is done, m'lord."
    $this->getUser()->setFlash('notice', 'Students imported successfully.');
    $this->redirect('project/tool');
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
    
  public function executeChangeDeadline(sfWebRequest $request)
  {
    try {
      // PHP Erro handling is really, really horrible
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
    
    
  public function executeDeleteStudent(sfWebRequest $request)
  {
    $conn = Doctrine_Manager::getInstance();
    $students = Doctrine_Core::getTable('StudentUser')->findAll();
    $users = Doctrine_Core::getTable('sfGuardUser')->findAll();
    
    $dnum = $request->getPostParameter('snum');
    foreach($students as $s)
    {
        if(strcasecmp($s['snum'], $dnum) == 0)
        {
            $s->delete();
            break;
        }
    }
       
    foreach($users as $u)
    {
        if(strcasecmp($u['username'], $dnum) == 0)
        {
            $u->delete();
            break;
        }
    } 
      
    $this->getUser()->setFlash('notice', 'Student "'.$dnum.'" deleted.');
    $this->redirect('project/tool');
  }

  // Delete all students and their login details in the database
  public function executeClearAllStudents(sfWebRequest $request)
  {
    $conn = Doctrine_Manager::getInstance();
    $students = Doctrine_Core::getTable('StudentUser')->findAll();
    $students->delete();
    
    $users = Doctrine_Core::getTable('sfGuardUser')->findAll();
    foreach ($users as $user) {
      if (!$user->getIsSuperAdmin())
        $user->delete();
    }

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

    // FIXME: Change the host link to the correct one
    $message = "Dear " . $guard_user->getFirstName() . "," . PHP_EOL . PHP_EOL . 
               "Your account has been created for the Project Allocation and Nomination System." . PHP_EOL . PHP_EOL .
               "Username: " . $snum . PHP_EOL .
               "Password: " . $password . PHP_EOL . PHP_EOL  .
               "Please follow the link to access the system." . PHP_EOL .
               "http://" . $this->getRequest()->getHost() . 
               $this->getRequest()->getRelativeUrlRoot() . PHP_EOL . PHP_EOL .
               "Thanks,\nProject Allocation and Nomination System (PANS)" . PHP_EOL . PHP_EOL .
               "If you are not enrolled in 3001ICT Third Year Project but received this email, please contact " .
               $this->getUser()->getGuardUser()->getEmailAddress() . "." ;

    $headers = 'From: ' . $this->getUser()->getGuardUser()->getName() . ' <' . $this->getUser()->getGuardUser()->getEmailAddress() . '>' . PHP_EOL . 'X-Mailer: PHP-' . phpversion() . PHP_EOL;
    
    $result = mail( $guard_user->getEmailAddress(),
                    "3001ICT - Your password has been created for project nominations",
                    $message,
                    $headers);
        
    if ($result == false) 
      return $snum;
    else 
      return null;
  }
  
  
  /*------------------------------------------------------------------
   Mass email every student their resetted passwords
  */
  public function executeEmailAllPasswords()
  {
    $failed_emails = array();
    $students = Doctrine_Core::getTable('StudentUser')->findAll();
    foreach ($students as $student) {
      $r = $this->emailPassword($student->getSnum(), $student->getFirstName(), $student->getLastName());
      if ($r != null)
        $failed_emails[] = $r;
    }
    if (count($failed_emails) == 0)
      $this->getUser()->setFlash('notice', 'Passwords have been reset.  Emails sent.');
    else
      $this->getUser()->setFlash('error', 'Passwords have been reset.  Some emails sent.  The following students did not receieve the email: ' . implode(", ", $failed_emails) . '.');
    $this->redirect('project/tool');
  }

  
 /* E-mail an individual student user their password function ---- NOT WORKING!!! */
  public function executEmailPassword()
  {
    $failed_emails = array();
    $students = Doctrine_Core::getTable('StudentUser')->findAll();
    while ($students = $student) {
      $r = $this->emailPassword($student->getSnum(), $student->getFirstName(), $student->getLastName());
      if ($r != null)
        $failed_emails[] = $r;
    }
    if (count($failed_emails) == 0)
      $this->getUser()->setFlash('notice', 'Your password has been reset.  Email sent.');
    else
      $this->getUser()->setFlash('error', 'No e-mails were sent, there was an issue.');
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


