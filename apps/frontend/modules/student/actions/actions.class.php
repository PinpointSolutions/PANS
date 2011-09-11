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
  public function executeIndex(sfWebRequest $request)
  {
    /* TODO: refactor this code to its own admin module */
    /* Check if the user is an admin user. If it's an admin user, the system 
       shows every student in one page. Otherwise, only show one student. 
       TODO: Show the nomination form status to the student user. */
    $this->admin = $this->getUser()->isSuperAdmin();
    if ($this->admin == true) {
      $this->student_users = Doctrine_Core::getTable('StudentUser')
      ->createQuery('a')
      ->execute();
    } else {    
      $this->redirect('student/show');
      /* 
      $this->username = $this->getUser()->getUsername();
      $this->student_users = Doctrine_Core::getTable('StudentUser')
                             ->createQuery('student_users')
                             ->where('student_users.snum = ?', $this->username)
                             ->execute();
      */
    }
    $this->forward404Unless($this->student_users);
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->admin = $this->getUser()->isSuperAdmin();
    if ($this->admin == true) {
      $this->student_user = Doctrine_Core::getTable('StudentUser')
                            ->find(array($request->getParameter('snum')));
    } else {    
      $this->username = $this->getUser()->getUsername();
      $this->student_user = Doctrine_Core::getTable('StudentUser')
                            ->find(array($this->username));
    }
    $this->forward404Unless($this->student_user);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new StudentUserForm();
  }

  /* TODO: Refactor into Admin backend */
  public function executeTest(sfWebRequest $request)
  {
    /* Get database connection */
    $conn = Doctrine_Manager::getInstance();
    $student_user = Doctrine_Core::getTable('StudentUser');
    $this->collection = new Doctrine_Collection('StudentUser');
    
    $students = array(
      array('snum' => 987653,
            'first_name' => 'Candy',
            'last_name' => 'Man'),
      array('snum' => 4328675,
            'first_name' => 'Chocolate',
            'last_name' => 'Bar')
    );
    
    foreach ($students as $student) {
      $user = new StudentUser();
      $user->snum = $student['snum'];
      $user->first_name = $student['first_name'];
      $user->last_name = $student['last_name'];
      $this->collection->add($user);
    }
    $this->collection->save();
    $this->msg = $this->collection;
  }
  
  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404();
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->username = $this->getUser()->getUsername();
    $this->student_user = Doctrine_Core::getTable('StudentUser')
                            ->find(array($this->username));
    $this->form = new StudentUserForm($this->student_user);
    $this->forward404Unless($this->student_user);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->username = $this->getUser()->getUsername();
    
    /* Check if the user is the correct one we're updating */
    if ($request->getParameter('snum') != $this->username) {
      $this->forward404('Oops. Your username doesn\'t match with your login.');
    }
    
    $this->forward404Unless($request->isMethod(sfRequest::POST) 
                         || $request->isMethod(sfRequest::PUT));
    $student_user = Doctrine_Core::getTable('StudentUser')
                                     ->find(array($this->username));
    $this->form = new StudentUserForm($student_user);
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
      $this->getUser()->setFlash('notice', 'Thanks!');
      $this->redirect('student/edit');
    }
  }
}
