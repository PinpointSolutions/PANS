<?php

/**
 * major actions.
 *
 * @package    PANS
 * @subpackage major
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class majorActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->Majors = Doctrine_Core::getTable('Majors')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->Major = Doctrine_Core::getTable('Majors')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->Major);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new MajorsForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new MajorsForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($Major = Doctrine_Core::getTable('Majors')->find(array($request->getParameter('id'))), sprintf('Object Major does not exist (%s).', $request->getParameter('id')));
    $this->form = new MajorsForm($Major);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($Major = Doctrine_Core::getTable('Majors')->find(array($request->getParameter('id'))), sprintf('Object Major does not exist (%s).', $request->getParameter('id')));
    $this->form = new MajorsForm($Major);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($Major = Doctrine_Core::getTable('Majors')->find(array($request->getParameter('id'))), sprintf('Object Major does not exist (%s).', $request->getParameter('id')));
    $Major->delete();

    $this->redirect('major/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $Major = $form->save();

      $this->redirect('major/edit?id='.$Major->getId());
    }
  }
}
