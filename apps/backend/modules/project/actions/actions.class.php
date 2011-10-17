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
    //$this->email = $this->getUser()->getGuardUser()->getEmailAddress();

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
  
  // Admin View for the Group Page, not affecting the 'manage/edit allocations' page
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
      
      //call to the email 
      $emailAttempt = $this -> emailPassword($user->snum, $user->first_name, $user->last_name);
      if ($emailAttempt != null)
      {
        $this->getUser()->setFlash('notice', 'Imported Student Successfully. !! Email NOT successfuly sent !!');
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
    $this->email_domain_collection = new Doctrine_Collection('EmailDomain');//FIXME

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
      $guard_user->setEmailAddress('s' . $user->getSnum() . '@'.$this->email_options_collection('domain'); //FIXME griffithuni.edu.au');
      $guard_user->setUsername($user->getSnum());
      $guard_user->setPassword($password); 
      $guard_user->setFirstName($user->getFirstName());
      $guard_user->setLastName($user->getLastName());
      $guard_user->setIsActive(true);
      $guard_user->setIsSuperAdmin(false);