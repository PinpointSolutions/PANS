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
  public function executeListStudentsFromFile(sfWebRequest $request)
  {
    $this->getUser()->setFlash('notice', 'Students have been added.');
    $this->redirect('student/index');
  }
  
  public function executeNewStudent(sfWebRequest $request)
  {
    $this->getUser()->setFlash('notice', 'Students have been added.');
    $this->redirect('student/index');
  }
}
