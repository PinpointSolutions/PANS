<?php

/**
 * allocations actions.
 *
 * @package    PANS
 * @subpackage allocations
 * @author     Daniel Brose
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class allocationsActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->project_allocationss = Doctrine_Core::getTable('ProjectAllocations')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->project_allocations = Doctrine_Core::getTable('ProjectAllocations')->find(array($request->getParameter('project_id')));
    $this->forward404Unless($this->project_allocations);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ProjectAllocationsForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new ProjectAllocationsForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($project_allocations = Doctrine_Core::getTable('ProjectAllocations')->find(array($request->getParameter('project_id'))), sprintf('Object project_allocations does not exist (%s).', $request->getParameter('project_id')));
    $this->form = new ProjectAllocationsForm($project_allocations);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($project_allocations = Doctrine_Core::getTable('ProjectAllocations')->find(array($request->getParameter('project_id'))), sprintf('Object project_allocations does not exist (%s).', $request->getParameter('project_id')));
    $this->form = new ProjectAllocationsForm($project_allocations);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($project_allocations = Doctrine_Core::getTable('ProjectAllocations')->find(array($request->getParameter('project_id'))), sprintf('Object project_allocations does not exist (%s).', $request->getParameter('project_id')));
    $project_allocations->delete();

    $this->redirect('allocations/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $project_allocations = $form->save();

      $this->redirect('allocations/edit?project_id='.$project_allocations->getProjectId());
    }
  }
}
