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
    try {
      $this->deadline = Doctrine_Core::getTable('NominationRound')
        ->createQuery('a')
        ->fetchOne();
    } catch (Exception $e) {
      $this->deadline = null;
    }

    if ($this->deadline) 
      $this->deadline = $this->deadline->getDeadline();
    else 
      $this->deadline = 'YYYY-MM-DD';

    try {
      $this->domain = Doctrine_Core::getTable('EmailDomain')
        ->createQuery('a')
        ->fetchOne();
    } catch (Exception $e) {
      $this->domain = null;
    }

    if ($this->domain) 
      $this->domain = $this->domain->getDomain();
    else 
      $this->domain = 'YYYY-MM-DD';//default value if empty
  }


  // Admin View for the Group Page, not affecting the 'manage/edit allocations' page
  public function executeGroup(sfWebRequest $request)
  {
    $this->groups = Doctrine_Core::getTable('ProjectAllocation')
      ->createQuery('a')
      ->execute();

    $this->p = Doctrine_Core::getTable('Project')
      ->createQuery('a')
      ->execute();
    $this->s = Doctrine_Core::getTable('StudentUser')
      ->createQuery('a')
      ->execute();

    $this->projects = array();
    $this->students = array();

    foreach ($this->p as $project) {
      $this->projects[$project->getId()] = $project;
    }

    foreach ($this->s as $student) {
      $this->students[$student->getSnum()] = $student;
    }
  }

  ////////////////////////////////////////////////////////////////////////
  // Scripts 

  /*------------------------------------------------------------------
    Changes the email domain used to create email addresses
  */
  public function executeChangeDomain(sfWebRequest $request)
  {
    try {
      $domain = $request->getPostParameter('domain');
      if (!$domain)
        throw new Exception();
    } catch (Exception $e) {
      $this->getUser()->setFlash('error', 'Invalid domain. Please verify.');
      $this->redirect('project/tool');
    }

    $conn = Doctrine_Manager::getInstance();
    $current = Doctrine_Core::getTable('EmailDomain')->findAll();
    try {
      $current->delete();
    } catch (Exception $e) {
      $this->getUser()->setFlash('error', 'Unexpected Error. Message: '.$e->getMessage());
      $this->redirect('project/tool');     
    }
    
    $current = new EmailDomain();
    $current->setDomain($domain);
    $current->save();

    $this->getUser()->setFlash('notice', 'New email domain applied.');
    $this->redirect('project/tool');
  }  


  /*------------------------------------------------------------------
    Changes the nomination round deadline
  */
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
    } catch (Exception $e) {
      $this->getUser()->setFlash('error', 'Unexpected Error. Message: '.$e->getMessage());
      $this->redirect('project/tool');   
    }
    
    $round = new NominationRound();
    $round->setDeadline($deadline->format('Y-m-d'));
    $round->save();

    $this->getUser()->setFlash('notice', 'New date applied.');
    $this->redirect('project/tool');
  }  
      

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

    //FIXME - the reason that 'invalid file type' errors are appearing is that you cannot declare the first cell to contain just 'ID'. This is a identifier for a different file type, hence the errors
      $fields = array(
        'Name',
        'ID',  
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
          $r->getFirstName() . ' ' . $r->getLastName(),
          $r->getSnum(),
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
      $this->getUser()->setFlash('notice', 'Successfully saved.  <a href="http://' .$this->getRequest()->getHost() . $this->getRequest()->getRelativeUrlRoot() . '/' . $fpath .'"> Click to download.</a>');
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

      // fetch the first 'domain' from the table and set it to $domain
      try {
        $domain = Doctrine_Core::getTable('EmailDomain')->createQuery('a')->fetchOne()->getDomain();
      } catch (Exception $e) {
        $this->getUser()->setFlash('error', 'Unable to find a domain record. Please check the email_domain table.');
        $this->redirect('project/tool');
      }
      
      $guard_user = new sfGuardUser();
      $password = $this->random_password();
      
      $guard_user->setEmailAddress('unused@email.address');
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
      
      //call to the email 
      $emailAttempt = $this->emailPassword($user->snum, $user->first_name, $user->last_name);
      if ($emailAttempt != null)//null is recieved if successful, $snum if not
      {
        $this->getUser()->setFlash('error', 'Added Student Successfully. Email NOT sent to s' . $formData['snum'] . '@'. $domain);
        $this->redirect('project/tool');
      }
      else 
      {
        //notice including the added student number
        $this->getUser()->setFlash('notice', 'Student "'.$formData['snum'].'" added successfully.');
        $this->redirect('project/tool');
      }
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

    //fetch the first 'domain' from the table and set it to $domain
    $domain = Doctrine_Core::getTable('EmailDomain')->createQuery('a')->fetchOne()->getDomain();

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
        //if an exception is caught we present a msg and redirect, also stopping this function
        $this->getUser()->setFlash('error', 'Failed to import students. Message: ' . $e->getMessage());
        $this->redirect('project/tool');
      }
      if ($user->getSnum() != 0)
      {
        $this->student_user_collection->add($user);
      }

      
      $guard_user = new sfGuardUser();
      $password = $this->random_password();
      $guard_user->setEmailAddress('s' . $user->getSnum() . '@'.$domain);
      $guard_user->setUsername($user->getSnum());
      $guard_user->setPassword($password); 
      $guard_user->setFirstName($user->getFirstName());
      $guard_user->setLastName($user->getLastName());
      $guard_user->setIsActive(true);
      $guard_user->setIsSuperAdmin(false);
      if ($user->getSnum() != 0)
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

      // Q: Are we actually emailing them in this version? if not remember to add functionality - and as i said above in addStudents, we seem to have all of the sfGuard code in email funciton anyway, why do we also have it in both of these functions?
      // A: No, probably not. The import can fail and it's better to email them later.

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
    Return null on success.
  */
  protected function emailPassword($snum, $first_name, $last_name)
  {
    $conn = Doctrine_Manager::getInstance();
    $guard_user = Doctrine_Core::getTable('sfGuardUser')->findOneBy('username', array($snum));
    
    //fetch the first 'domain' from the table and set it to $domain
    $domain = Doctrine_Core::getTable('EmailDomain')->createQuery('a')->fetchOne()->getDomain();

    if ($guard_user == null)
      $guard_user = new sfGuardUser();//FIXME this step useful but only if used as part of the import/addStudents functions. As it is this is just unnecessary as is the following code which is repeated in all 3 functions

    $email = 's' . $snum . '@'.$domain;

    $password = $this->random_password();
    $guard_user->setUsername($snum);
    $guard_user->setPassword($password);
    $guard_user->setFirstName($first_name);
    $guard_user->setLastName($last_name);
    $guard_user->setIsActive(true);
    $guard_user->setIsSuperAdmin(false);
    
    $guard_user->save();

    $message = "Dear " . $guard_user->getFirstName() . "," . PHP_EOL . PHP_EOL . 
               "Your account has been created for the Project Allocation and Nomination System." . PHP_EOL . PHP_EOL .
               "Username: " . $snum . PHP_EOL .
               "Password: " . $password . PHP_EOL . PHP_EOL  .
               "Please follow the link to access the system." . PHP_EOL .
               "http://" . $this->getRequest()->getHost() . 
               $this->getRequest()->getRelativeUrlRoot() . PHP_EOL . PHP_EOL .
               "Thanks,\nProject Allocation and Nomination System (PANS)" . PHP_EOL . PHP_EOL;

    $headers = 'From: ' . $this->getUser()->getGuardUser()->getName() . ' <noreply@' . $domain . '>' . PHP_EOL . 'X-Mailer: PHP-' . phpversion() . PHP_EOL;
    
    $result = mail( $email,
                    "3001ICT - Your password has been created for project nominations",
                    $message,
                    $headers);
        
    if ($result == false) 
      return $snum;
    else 
      return null;
  }


  /*------------------------------------------------------------------
    Send the student an email reminder about completing the form.
  */
  protected function emailReminder($snum, $first_name, $last_name)
  {
    $conn = Doctrine_Manager::getInstance();
    $domain = Doctrine_Core::getTable('EmailDomain')->createQuery('a')->fetchOne()->getDomain();
    $email = 's' . $snum . '@'.$domain;

    $message = "Dear " . $first_name . "," . PHP_EOL . PHP_EOL . 
               "I noticed you haven't completed the project nomination form.  Could you do me a favour and spare 5 minutes?  You should already have received your login details in a previous email I sent you." . PHP_EOL . PHP_EOL .
               "Please follow the link to access the system." . PHP_EOL .
               "http://" . $this->getRequest()->getHost() . 
               $this->getRequest()->getRelativeUrlRoot() . PHP_EOL . PHP_EOL .
               "Thanks,\nProject Allocation and Nomination System (PANS)" . PHP_EOL . PHP_EOL;

    $headers = 'From: ' . $this->getUser()->getGuardUser()->getName() . ' <noreply@' . $domain . '>' . PHP_EOL . 'X-Mailer: PHP-' . phpversion() . PHP_EOL;
    
    $result = mail( $email,
                    "3001ICT - You haven't completed the form yet",
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
  public function executeEmailAllPasswords(sfWebRequest $request)
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


  /*------------------------------------------------------------------
   Mass email students who hasn't completed their forms yet
  */
  public function executeEmailReminders(sfWebRequest $request)
  {
    $failed_emails = array();
    $students = Doctrine_Core::getTable('StudentUser')->findAll();
    foreach ($students as $student) {
      if (!$student->getFormCompleted()) {
        $r = $this->emailReminder($student->getSnum(), $student->getFirstName(), $student->getLastName());
        if ($r != null)
          $failed_emails[] = $r;
      }
    }
    if (count($failed_emails) == 0)
      $this->getUser()->setFlash('notice', 'Emails sent.');
    else
      $this->getUser()->setFlash('error', 'Some emails failed to send.  The following students did not receieve the email: ' . implode(", ", $failed_emails) . '.');
    $this->redirect('project/tool');
  }

  
  /* E-mail an individual student user their password function */
  public function executeEmailPassword(sfWebRequest $request)
  {
    $students = Doctrine_Core::getTable('StudentUser')->findAll();
    if (!$students) {
      $this->getUser()->setFlash('error', 'No students are in the system.');
      $this->redirect('project/tool');
    }

    $r = $this->emailPassword($student->getSnum(), $student->getFirstName(), $student->getLastName());
    if ($r == null)
      $this->getUser()->setFlash('notice', 'Your password has been reset.  Email sent.');
    else
      $this->getUser()->setFlash('error', 'Oops.  An error occured.');
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
    $characters = '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
    $string = '';    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
  }
}


