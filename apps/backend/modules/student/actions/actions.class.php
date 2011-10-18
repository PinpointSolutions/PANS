<?php

require_once dirname(__FILE__).'/../lib/studentGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/studentGeneratorHelper.class.php';

/**
 * student actions.
 *
 * @package    PANS
 * @subpackage student
 * @author     Pinpoint Solutions
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class studentActions extends autoStudentActions
{
  // This action confuses symfony's routing.  All student actions are now in project.
  // Two strikes against symfony's parsing. =|
  
  //This function stops student users from logging into the backend.
  public function executeIndex(sfWebRequest $request) {
    parent::executeIndex($request);
    $this->admin = $this->getUser()->isSuperAdmin();
    if ($this->admin == false) {
        $this->redirect('/logout');
    }
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->student_user = $this->getRoute()->getObject();
    $this->student_user->setDegreeIds(explode(' ', $this->student_user->getDegreeIds()));
    $this->student_user->setMajorIds(explode(' ', $this->student_user->getMajorIds()));
    $this->student_user->setSkillSetIds(explode(' ', $this->student_user->getSkillSetIds()));
    $this->form = $this->configuration->getForm($this->student_user);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->student_user = $this->getRoute()->getObject();
    $this->form = $this->configuration->getForm($this->student_user);
    $this->setTemplate('edit');
    
    $params = $request->getParameter($this->form->getName());
    if (array_key_exists('degree_ids', $params))
      $params['degree_ids'] = implode(' ', $params['degree_ids']);
    if (array_key_exists('major_ids', $params))
      $params['major_ids'] = implode(' ', $params['major_ids']);
    if (array_key_exists('skill_set_ids', $params))
      $params['skill_set_ids'] = implode(' ', $params['skill_set_ids']);
    $params['form_completed'] = true;
    $request->setParameter($this->form->getName(), $params);

    $this->processForm($request, $this->form);
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

      try {
        $student_user = $form->save();
      } catch (Doctrine_Validator_Exception $e) {

        $errorStack = $form->getObject()->getErrorStack();

        $message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ?  's' : null) . " with validation errors: ";
        foreach ($errorStack as $field => $errors) {
            $message .= "$field (" . implode(", ", $errors) . "), ";
        }
        $message = trim($message, ', ');

        $this->getUser()->setFlash('error', $message);
        return sfView::SUCCESS;
      }

      $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $student_user)));

      if ($request->hasParameter('_save_and_add'))
      {
        $this->getUser()->setFlash('notice', $notice.' You can add another one below.');

        $this->redirect('@student_user_new');
      }
      else
      {
        $this->getUser()->setFlash('notice', $notice);

        $this->redirect(array('sf_route' => 'student_user_edit', 'sf_subject' => $student_user));
      }
    }
    else
    {
      $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
    }
  }
}
