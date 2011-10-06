<?php

/**
 * student actions.
 *
 * @package    PANS
 * @subpackage student
 * @author     Pinpoint Solutions
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class studentActions extends sfActions
{
  // By default, uses the Edit() function to show the form
  public function executeIndex(sfWebRequest $request)
  {
    $this->admin = $this->getUser()->isSuperAdmin();
    if ($this->admin == true) {
      $this->student_users = Doctrine_Core::getTable('StudentUser')
      ->createQuery('a')
      ->execute();
    } else {    
      $this->redirect('student/edit');
    }
    $this->forward404Unless($this->student_users);
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->redirect404();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->redirect404();
  }
  
  public function executeCreate(sfWebRequest $request)
  {
    $this->redirect404();
  }

  public function executeEdit(sfWebRequest $request)
  {
    // Only display the record of the student logged in
    $this->username = $this->getUser()->getUsername();
    $this->student_user = Doctrine_Core::getTable('StudentUser')
                            ->find(array($this->username));
    if (!$this->student_user)
      $this->redirect404();

    // If you modify the following code, the system won't break.  It might
    // not display the form properly, though.
    $this->student_user
         ->setDegreeIds(explode(' ', $this->student_user->getDegreeIds()));
    $this->student_user
         ->setMajorIds(explode(' ', $this->student_user->getMajorIds()));
    $this->student_user
         ->setSkillSetIds(explode(' ', $this->student_user->getSkillSetIds()));
    // From here on, you're out of the Danger Zone (TM).
    
    // Create the form, and point the autocompletion to the ajax helper
    $this->form = new StudentUserForm($this->student_user,
       array('url' => $this->getController()->genUrl('student/ajax')));
    
    try {
      $this->allowed = $this->checkDeadline();
    } catch (Exception $e) {
      $this->getUser()->setFlash('notice', 'No deadline has been set.');
      return;
    }

    if (!$this->getUser()->hasFlash('error') && !$this->getUser()->hasFlash('notice')) {
      if (!$this->allowed)
        $this->getUser()->setFlash('notice', 'The deadline was on ' . $this->deadline->getDeadline() . '.  The form is now read-only.');
      else
        $this->getUser()->setFlash('notice', 'Remember, this form is due on ' . $this->deadline->getDeadline() . '.');
    }
  }

  /* For Autocompletion, we retrieve a list of names as JSON. */
  public function executeAjax(sfWebRequest $request)
  {
    $this->getResponse()->setContentType('application/json');
    $students = StudentUser::retrieveForSelect($request->getParameter('q'),
                                               $request->getParameter('limit'));
    return $this->renderText(json_encode($students));
  }

  public function executeUpdate(sfWebRequest $request)
  {
    // Check if the user is the correct one we're updating
    $this->username = $this->getUser()->getUsername();
    if ($request->getParameter('snum') != $this->username) {
      $this->redirect404();
    }

    // Check for deadline
    if (!$this->getUser()->isSuperAdmin() && !$this->checkDeadline()) {
      $this->getUser()->setFlash('error', 'You cannot modify this form after ' . $this->deadline->getDeadline() . '.');
      $this->redirect('student/edit');
    }
   
    // Redirect to 404 if the request came from strange places. 
    $this->forward404Unless($request->isMethod(sfRequest::POST) 
                         || $request->isMethod(sfRequest::PUT));

    // Create the form object for processing
    $student_user = Doctrine_Core::getTable('StudentUser')
                                     ->find(array($this->username));
    $this->form = new StudentUserForm($student_user);
    
    // BEGIN SQL DATABASE HACK. DRAGONS ABOUND AND MONSTERS LEAP FROM THE DARK.
    //
    // SQL Databases are not built for schema changing.  Here we are giving
    // the admin user the ability to add and remove degrees and majors.  This
    // means each student needs to related to one or more degrees or majors,
    // and the number of degrees or majors may change at any given time.  Even
    // better, the major might even just disappear.
    //
    // The point is, even if we created a relation with a table, we still have
    // to deal with the fields (columns) disappearing if the admin user decided
    // to screw with the system.
    //
    // The easy way out is to store such a 1-N relation as a string, delimited
    // by whitespaces.  On save we implode the array into such a string.  On
    // load we simply explode it back into an array, and Symfony can deal with
    // it.
      
    // Grab a copy of the requests and do some post-POST-processing, and then
    // Copy it back in before the form is saved.
    $params = $request->getParameter($this->form->getName());
    $params['degree_ids'] = implode(' ', $params['degree_ids']);
    $params['major_ids'] = implode(' ', $params['major_ids']);
    $params['skill_set_ids'] = implode(' ', $params['skill_set_ids']);
    $request->setParameter($this->form->getName(), $params);
    // END SQL DATABASE HACK.  CONGRATULATIONS.  YOU LIVE.  FOR NOW.
    
    // Process the form, and redirect back to the edit page. 
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
    $this->redirect('student/edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    throw new sfError404Exception();
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $student_user = $form->save();
      $this->getUser()->setFlash('notice', 'Successfully Saved!');
      $this->redirect('student/edit');
    }
  }

  // Check for deadline.  If it's past the date, return false
  protected function checkDeadline()
  {
    try {
      $this->deadline = Doctrine_Core::getTable('NominationRound')
        ->createQuery('a')
        ->fetchOne();
    } catch (Exception $e) {
      $this->deadline = null;
    }
    if (!$this->deadline) 
      throw new Exception('No deadline has been set');
    
    $today = new DateTime();
    $deadline = DateTime::createFromFormat('Y-m-d', $this->deadline->getDeadline());

    return ($deadline > $today);
  }
}
