<?php

/**
 * project actions.
 *
 * @package    PANS
 * @subpackage project
 * @author     Pinpoint Solutions
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class projectActions extends sfActions
{
  /**
    * Displays the projects in a list view
    */
  public function executeIndex(sfWebRequest $request)
  {
    $this->projects = Doctrine_Core::getTable('Project')
      ->createQuery('a')
      ->execute();
      
    $this->majors = Doctrine_Core::getTable('Major')
      ->createQuery('a')
      ->execute();
    $this->majorName = array();
    foreach($this->majors as $m){
      $this->majorName[$m->getId()] = $m->getMajor();
    }
    }
  
  /**
   * Displays one project
   */
  public function executeShow(sfWebRequest $request)
  {
    $this->forward404(); 
  }
}
