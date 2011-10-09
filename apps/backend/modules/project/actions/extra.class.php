<?php
  /*------------------------------------------------------------------
  Export  database tables to CSV files
  This currently treats 3 different tables- the projects, students and the allocations
  The actual treated data depends on a $_POST variable captured from a dropdown <select>
  */
  public function executeExportTables_NOTINUSE(sfWebRequest $request)
  {  
  //TODO:
    // test thoroughly, mostly to see if all special character types are escaped
  
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
    $info .= 'Project id#'."," . 'Title'."," . 'Organisation'."," . 'Description'."," . 'More Info'."," . 'GPA Cutoff'."," . 'Major ID\'s'."," . 'Skill Set ID\'s'."," . "\n";
      foreach($rows as $r) {
        $info .=  $r['id']. ",";
        $info .=  '"'.$r['title'].'"' . ",";
        $info .=  '"'.$r['organisation'].'"' . ",";
        $info .=  '"'.$r['description'].'"' . ",";
        $info .=  $r['has_additional_info']. ",";
        $info .=  $r['has_gpa_cutoff']. ",";
        $info .=  $r['major_ids']. ",";
        $info .=  $r['skill_set_ids']. "\n"; 
      } 
        
  }
  else if($opt=='students')
  {
     $rows = Doctrine_Core::getTable('StudentUser')->findAll();
       $info .= 'S Number'."," //any changes to this first cell MUST be reflected in importStudents()
    . 'First Name'."," 
    . 'Last Name'."," 
    . 'Pass/Fail PM'.","
    . 'Degree'.","
    . 'Major'.","
    . 'Skills'.","
    . 'GPA'.","
    . 'Proj Pref 1'.","
    . 'Proj Just 1'.","
  /*  . 'Proj Pref 2'.","
    . 'Proj Just 2'.","
    . 'Proj Pref 3'.","
    . 'Proj Just 3'.","
    . 'Proj Pref 4'.","
    . 'Proj Just 4'.","
    . 'Proj Pref 5'.","
    . 'Proj Just 5'.","
    . 'Pref Stud 1'.","
    . 'Pref Stud 2'.","
    . 'Pref Stud 3'.","
    . 'Pref Stud 4'.","
    . 'Pref Stud 5'.","
    . 'Not Pref 1'.","
    . 'Not Pref 2'.","
    . 'Not Pref 3'.","
    . 'Not Pref 4'.","
    . 'Not Pref 5'.","
  */
    . "\n";
      
      foreach($rows as $r) {
      
      $info .=  $r['snum'] . ",".
      '"'.$r['first_name'].'"' . ",".
      '"'.$r['last_name'].'"' . ",".
         $r['pass_fail_pm'] . ",".
         $r['degree_ids'] . ",".
         $r['major_ids'] . ",".
         $r['skill_set_ids'] . ",".
         $r['gpa'] . ",".
          $r['proj_pref1'] . ",".
        $r['proj_just1'] . ",".
   /*      $r['proj_pref2'] . ",".

         '"'.$r['proj_just2'].'"' . ",".
       $r['proj_pref3'] . ",".
         '"'.$r['proj_just3'].'"' . ",".
        $r['proj_pref4'] . ",".
         '"'.$r['proj_just4'].'"' . ",".
         $r['proj_pref5'] . ",".
         '"'.$r['proj_just5'].'"' . ",".
         $r['y_stu_pref1'] . ",".
         $r['y_stu_pref2'] . ",".
         $r['y_stu_pref3'] . ",".
         $r['y_stu_pref4'] . ",".
         $r['y_stu_pref5'] . ",".
         $r['n_stu_pref1'] . ",".
         $r['n_stu_pref2'] . ",".
         $r['n_stu_pref3'] . ",".
         $r['n_stu_pref4'] . ",".
         $r['n_stu_pref5'].

    */
        "\n";
        
    }
   // htmlspecialchars_decode($info);
    
  }
  else if($opt=='groups')
  {
    $rows = Doctrine_Core::getTable('ProjectAllocation')->findAll();
    $info .= 'Group ID'."," . 'Proj ID'."," . 'Stud No\'s'."," . "\n";
      foreach($rows as $r) {
      $info .=  $r['id'] . ", ";
        $info .=  $r['project_id'] . ", ";
      $info .=  $r['snum'] . "\n";
    }
  }
  
  //escape standard special characters, the '"'. above escapes unwanted commas
  addslashes($info);//seems to work
  
  // This tells it to use the csv.php template file
  // note- all content is still contained within $sf_content
  // see templates/csv.php to see how to treat $sf_content
  $this->setlayout('csv');
  
  // print out the results to this new page layout
  print($info);
 // $this->getResponse()->clearHttpHeaders();
  
  // set up the httpHeaders to properly treat the page

  //SAME PROBLEM WITH THE NUMBER OF $r[] calls, no matter how setup

//header('Content-type: application/vnd.ms-excel');
//header('Content-Disposition: attachment; filename="downloaded.csv"');

    $this->getResponse()->clearHttpHeaders();
    $this->getResponse()->setHttpHeader('Content-Type', 'application/vnd.ms-excel');//Content-Type: text/comma-separated-values
    $this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename=PANS_'.$opt.'List_'.date("Y-m-d").'.csv');

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


?>