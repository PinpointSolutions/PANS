<?php

require_once dirname(__FILE__).'/../lib/groupGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/groupGeneratorHelper.class.php';

/**
 * Sorting student into groups.
 *
 * @package    PANS
 * @subpackage group
 * @author     Pinpoint Solutions
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class groupActions extends autoGroupActions
{
  public function executeAllocate(sfWebRequest $request)
  {
    // Load all the necessary data
    $students = Doctrine_Core::getTable('StudentUser')
      ->createQuery('a')
      ->execute();
    
    $projects = Doctrine_Core::getTable('Project')
      ->createQuery('a')
      ->execute();
    
    $this->result = '';

    // Grab student information for desired and undesired student ids
    $desires = array();
    $undesires = array();
    foreach ($students as $student) {
      $desires[$student->getSnum()] = array($student->getYStuPref1(),
                                            $student->getYStuPref2(),
                                            $student->getYStuPref3(),
                                            $student->getYStuPref4(),
                                            $student->getYStuPref5());
      $undesires[$student->getSnum()] = array($student->getNStuPref1(),
                                              $student->getNStuPref2(),
                                              $student->getNStuPref3(),
                                              $student->getNStuPref4(),
                                              $student->getNStuPref5());
    }
    $this->result = print_r($desires, true);
    $this->result .= print_r($undesires, true);
  }
}
