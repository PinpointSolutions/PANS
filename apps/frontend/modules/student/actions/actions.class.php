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
    $this->student_users = Doctrine_Core::getTable('StudentUser')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->student_user = Doctrine_Core::getTable('StudentUser')->find(array($request->getParameter('snum')));
    $this->forward404Unless($this->student_user);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new StudentUserForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new StudentUserForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($student_user = Doctrine_Core::getTable('StudentUser')->find(array($request->getParameter('snum'))), sprintf('Object student_user does not exist (%s).', $request->getParameter('snum')));
    $this->form = new StudentUserForm($student_user);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($student_user = Doctrine_Core::getTable('StudentUser')->find(array($request->getParameter('snum'))), sprintf('Object student_user does not exist (%s).', $request->getParameter('snum')));
    $this->form = new StudentUserForm($student_user);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($student_user = Doctrine_Core::getTable('StudentUser')->find(array($request->getParameter('snum'))), sprintf('Object student_user does not exist (%s).', $request->getParameter('snum')));
    $student_user->delete();

    $this->redirect('student/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $student_user = $form->save();

      $this->redirect('student/edit?snum='.$student_user->getSnum());
    }
  }
}