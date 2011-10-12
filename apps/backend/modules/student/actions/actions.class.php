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
        $this->redirect('guard/logout');
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

    $this->student_user->setFirstName("1 2 3");


    $this->student_user->setDegreeIds("1 2 3");
    $this->student_user->setMajorIds(implode(' ', $this->student_user->getMajorIds()));
    $this->student_user->setSkillSetIds(implode(' ', $this->student_user->getSkillSetIds()));

    $this->form = $this->configuration->getForm($this->student_user);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }
}
