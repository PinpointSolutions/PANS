<?php

/**
 * students actions.
 *
 * @package    PANS
 * @subpackage students
 * @author     Daniel Brose
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class studentsActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->student_userss = Doctrine_Core::getTable('StudentUsers')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->student_users = Doctrine_Core::getTable('StudentUsers')->find(array($request->getParameter('snum')));
    $this->forward404Unless($this->student_users);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new StudentUsersForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new StudentUsersForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($student_users = Doctrine_Core::getTable('StudentUsers')->find(array($request->getParameter('snum'))), sprintf('Object student_users does not exist (%s).', $request->getParameter('snum')));
    $this->form = new StudentUsersForm($student_users);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($student_users = Doctrine_Core::getTable('StudentUsers')->find(array($request->getParameter('snum'))), sprintf('Object student_users does not exist (%s).', $request->getParameter('snum')));
    $this->form = new StudentUsersForm($student_users);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($student_users = Doctrine_Core::getTable('StudentUsers')->find(array($request->getParameter('snum'))), sprintf('Object student_users does not exist (%s).', $request->getParameter('snum')));
    $student_users->delete();

    $this->redirect('students/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $student_users = $form->save();

      $this->redirect('students/edit?snum='.$student_users->getSnum());
    }
  }
}
