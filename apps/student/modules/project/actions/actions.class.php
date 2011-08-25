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
    $this->stuff = "stuff";
    $this->projects = Doctrine_Core::getTable('Project')
      ->createQuery('a')
      ->execute();
  }
  
  /**
   * Displays one project
   */
  public function executeShow(sfWebRequest $request)
  {
    /*$this->project = Doctrine_Core::getTable('Project')->find(array($request->getParameter('snum')));
    $this->forward404Unless($this->student_user); */
  }
}
