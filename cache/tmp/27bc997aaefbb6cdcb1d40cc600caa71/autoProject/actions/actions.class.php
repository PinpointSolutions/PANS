<?php

/**
 * project actions.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage project
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class autoProjectActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->majors = Doctrine_Core::getTable('Projects')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->projects = Doctrine_Core::getTable('Projects')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->projects);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ProjectsForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new ProjectsForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->find(array($request->getParameter('id'))), sprintf('Object projects does not exist (%s).', $request->getParameter('id')));
    $this->form = new ProjectsForm($projects);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->find(array($request->getParameter('id'))), sprintf('Object projects does not exist (%s).', $request->getParameter('id')));
    $this->form = new ProjectsForm($projects);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->find(array($request->getParameter('id'))), sprintf('Object projects does not exist (%s).', $request->getParameter('id')));
    $projects->delete();

    $this->redirect('project/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $projects = $form->save();

      $this->redirect('project/edit?id='.$projects->getId());
    }
  }
}
