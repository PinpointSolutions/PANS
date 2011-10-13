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
      $info = array('Project id#', 'Title', 'Organisation', 'Description', 'Has More Info?', 'Has GPA Cutoff?', 'Max Group Size?', 'Degree ID\'s', 'Major ID\'s', 'Skill Set ID\'s');
      
    }
    elseif($opt=='groups')
    {
      //ask symfony to return the data from one of our tables
      $rows = Doctrine_Core::getTable('ProjectAllocation')->findAll();
      $info = array('Group ID','Proj ID','S Number');
    }

    // Setup the file pointer. Notice no / at the start, this is needed for the link but has caused issues when added before the fopen was called
    $fpath = 'downloads/PANS_'.$opt.'List_'.date("Y-m-d").'.csv';
        //FIXME - ok, so the last problem i had with the download was that it did not correctly run fopen when the $fpath has a / at the start.
        // as this / was needed for the link it was added there and this seemed to fix it. Bizare as the / was added to fix this seemingly SAME issue... kill me now
    
    //we first check if the file exists. If it does we move on, if not...
    if(!file_exists(dirname($fpath)))//THIS LINE
    {
   
      //we then make it and check if this was successful, if not...
      if(!mkdir(dirname($fpath)))
      { 
         sfLogger::debug(dirname($fpath));
        //we provide feedback and redirect the user
        $this->getUser()->setFlash('error', 'Could not create directory. Please ensure you have folder privilages for "'.dirname($fpath).'"');
        $this->redirect('project/tool');
      }
    }
    //check if we can write to that file/directory
    if(!is_writable($fpath))
    {
        $this->getUser()->setFlash('error', 'Cannot write to file. Please ensure you have folder privilages for "'.dirname($fpath).'"');
        $this->redirect('project/tool');
    }

    //open the file
    $fp = fopen($fpath, 'w+');//or 'c', doesnt really matter
    
    //we then check if the fopen was successful, if not...
    if(!$fp) 
    {
    
      //we provide feedback and redirect the user
      $this->getUser()->setFlash('error', 'Could not create file "'.$fpath.'". Please ensure that if the file already exists it is not in use.');
      $this->redirect('project/tool');
    }
    else //if $fp
    {      
        //so if we got through with no errors

        //we write the first row using the headers
        fputcsv($fp, $info);
        
        //create an array to better work with symfony's data array return
        $data = array();
        
        //so iterate through and convert to normal array
        foreach($rows as $r)
        {
         $x = 0;
          foreach($r as $v)
          {
            $data[$x++] = $v;//adds value then increments $x
          }
          //this method parses the array and properly inputs it as csv format
          fputcsv($fp, $data);
        }
        
        //close the file as we are done now
        if(!fclose($fp))
        {
            $this->getUser()->setFlash('error', 'Operation not successful. File "'.$fpath.'" not created.');
            $this->redirect('project/tool');
        }
        else //fclose successful
        {
            //notify the user of the status and location of the file
            $this->getUser()->setFlash('notice', 'Successfully saved, <a href="http://' .$this->getRequest()->getHost() .'/'.  $fpath .'"> Click to download.</a>');
          
            //redirect to the tool page
            $this->redirect('project/tool');
        }
        
       
    }
    //this is a generic flash error, used for dev at one point and we should probably remove this if not needed 
    $this->getUser()->setFlash('error', 'Issue encountered');// FIXME
    $this->redirect('project/tool');
}



 /* ------------------------------------------------------------------
    
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
    $count = 0;// used in both cases below to define student[index's]


  //now check the header to determine how to handle

  // FIXME: This is really hacky. I'm not a fan but whatever.
  // We should employ error checking and not ... checking for an unused field.
  if(strcasecmp($firstLine[0],'N')==0)//this is always the first cell generated by learning@griffith, notice the [0]
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
        $students[$count] = array('snum' => $id, 
                              'first_name' => $firstName, 
                              'last_name' => $lastName);
        $count++;
      }
  }
  elseif(strcasecmp($firstLine[1],'S')==0)//this must match with the first cell as set in exportTables(), notice the [1]
  {  
    //PANS LAYOUT
    // Assume index 0 is ID, and each field follows as in the database. Store them as such.
    foreach ($raw_data as $line) 
    {
        $items = str_getcsv($line);
        if (count($items) < 2)
          continue;
    //two possible approaches
    //  1. to define exact structure
    //  2. to treat it the same way it exports
    // the second option makes more sense and, just 
    // like the export- works irrespective to database 
    // structure changes 

    //FIXME - this was/is not actually continueing through to how the data is actually being entered into the database - check below sections of code
 /*
        $x = 0;
        $students[$count] = array();
        foreach($items as $item)
        {
            $students[$count][$x++] = $item;
        }
        $count++;
         
   FIXME  - no longer needed if working
 
       */
        $x = 0;
        $students[$count++] = array(
          'snum'           =>     $items[$x++],
          'first_name'     =>     $items[$x++],
          'last_name'      =>     $items[$x++],
          'pass_fail_pm'   =>     $items[$x++],
          'major_ids'      =>     $items[$x++],
          'degree_ids'     =>     $items[$x++],
          'skill_set_ids'  =>     $items[$x++],
          'gpa'            =>     $items[$x++],
          'proj_pref1'     =>     $items[$x++],
          'proj_pref2'     =>     $items[$x++],
          'proj_pref3'     =>     $items[$x++],
          'proj_pref4'     =>     $items[$x++],
          'proj_pref5'     =>     $items[$x++],
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
          'flag'           =>     $items[$x++],
          'created_at'     =>     $items[$x++],
          'updated_at'     =>     $items[$x++]
        );

         
    }
  }
    
    
    // Get database connection
    $conn = Doctrine_Manager::getInstance();
    $this->guard_user_collection = new Doctrine_Collection('sfGuardUser');
    $this->student_user_collection = new Doctrine_Collection('StudentUser');
    
    // Add students
    foreach ($students as $student) 
    {
      $guard_user = new sfGuardUser();
      $password = $this->random_password();
      
      $guard_user->setEmailAddress('s' . $student['snum'] . '@griffithuni.edu.au');//FIXME
      $guard_user->setUsername($student['snum']);// 'snum'
      $guard_user->setPassword($password); 
      $guard_user->setFirstName($student['first_name']);// 'first_name'
      $guard_user->setLastName($student['last_name']);// 'last_name'
      $guard_user->setIsActive(true);
      $guard_user->setIsSuperAdmin(false);
      $this->guard_user_collection->add($guard_user);

      $user = new StudentUser();
      $user->snum = $student['snum'];// 'snum'
      $user->first_name = $student['first_name'];// 'first_name'
      $user->last_name = $student['last_name'];// 'last_name'


      // FIXME: Hackiness continued 
      elseif(strcasecmp($firstLine[1],'S')==0)
      {
//FIXME - i still like this idea but i until i find a way to easily see which actual field each $er is referring to i cant fix it 
        /*
        $ttt = 0;
      // $temp = array();
        foreach($user as $er)
        {
          $er = $student[$ttt++];   
          $temp[$ttt] = $er;
        }
       // sfLogger::debug($ttt);  //so it is iterating through the correct number of times
       // sfLogger::debug(count($student));
       // sfLogger::debug($temp);
       // sfLogger::debug($user);
 debug() results

$temp  - $temp[$ttt] = $er; 
at sfLogger->debug(array('2000000', 'Marcos', 'Ambrose', '', '', '', '', '0.00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2011-10-12 01:57:13', '2011-10-12 01:57:13'))

$student
at sfLogger->debug(array('2000000', 'Marcos', 'Ambrose', '', '', '', '', '0.00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2011-10-12 01:57:13', '2011-10-12 01:57:13'))

*/


// FIXME - currently it seems to be the contraints on these fields causing the issues
//sfLogger::debug($student);
        $user->pass_fail_pm= $student['pass_fail_pm'];
        $user->major_ids= $student['major_ids'];
        $user->degree_ids= $student['degree_ids'];
        $user->gpa= $student['gpa'];
    //    $user->proj_pref1= $student['proj_pref1'];
    //    $user->proj_pref2= $student['proj_pref2'];
    //    $user->proj_pref3= $student['proj_pref3'];
   //     $user->proj_pref4= $student['proj_pref4'];
    //    $user->proj_pref5= $student['proj_pref5'];
        $user->skill_set_ids= $student['skill_set_ids'];   
  //      $user->y_stu_pref1= $student['y_stu_pref1'];
    //    $user->y_stu_pref2= $student['y_stu_pref2'];
    //    $user->y_stu_pref3= $student['y_stu_pref3'];
    //    $user->y_stu_pref4= $student['y_stu_pref4'];
    //    $user->y_stu_pref5= $student['y_stu_pref5'];
     //   $user->n_stu_pref1= $student['n_stu_pref1'];
     //   $user->n_stu_pref2= $student['n_stu_pref2'];
     //   $user->n_stu_pref3= $student['n_stu_pref3'];
     //   $user->n_stu_pref4= $student['n_stu_pref4'];
     //   $user->n_stu_pref5= $student['n_stu_pref5'];
        $user->proj_just1= $student['proj_just1'] ;
        $user->proj_just2= $student['proj_just2'] ;
        $user->proj_just3= $student['proj_just3'] ;
        $user->proj_just4= $student['proj_just4'] ;
        $user->proj_just5= $student['proj_just5'] ;
        $user->form_completed= $student['form_completed'];
        $user->flag= $student['flag'];
        $user->created_at= $student['created_at'];
        $user->updated_at= $student['updated_at'];
  
        
      }
      $this->student_user_collection->add($user);
    }
  
    // Commit the new students into database
    try {
      //sfLogger::debug($this->student_user_collection->toString());

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


