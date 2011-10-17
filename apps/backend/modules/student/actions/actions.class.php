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
}
