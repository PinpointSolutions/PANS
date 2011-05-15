<?php

/**
 * job actions.
 *
 * @package    PANS
 * @subpackage job
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class jobActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->admin_userss = Doctrine_Core::getTable('AdminUsers')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->admin_users = Doctrine_Core::getTable('AdminUsers')->find(array($request->getParameter('username')));
    $this->forward404Unless($this->admin_users);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new AdminUsersForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new AdminUsersForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($admin_users = Doctrine_Core::getTable('AdminUsers')->find(array($request->getParameter('username'))), sprintf('Object admin_users does not exist (%s).', $request->getParameter('username')));
    $this->form = new AdminUsersForm($admin_users);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($admin_users = Doctrine_Core::getTable('AdminUsers')->find(array($request->getParameter('username'))), sprintf('Object admin_users does not exist (%s).', $request->getParameter('username')));
    $this->form = new AdminUsersForm($admin_users);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($admin_users = Doctrine_Core::getTable('AdminUsers')->find(array($request->getParameter('username'))), sprintf('Object admin_users does not exist (%s).', $request->getParameter('username')));
    $admin_users->delete();

    $this->redirect('job/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $admin_users = $form->save();

      $this->redirect('job/edit?username='.$admin_users->getUsername());
    }
  }
}
