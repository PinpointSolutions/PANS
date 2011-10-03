<?php

require_once dirname(__FILE__).'/../lib/projectGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/projectGeneratorHelper.class.php';

/**
 * project actions.
 *
 * @package    PANS
 * @subpackage project
 * @author     Pinpoint Solutions
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class projectActions extends autoProjectActions
{
  // Admin tools
  // Do not modify or remove this function
  public function executeTool(sfWebRequest $request)
  {
    $this->email = $this->getUser()->getGuardUser()->getEmailAddress();
  }
  
  // Admin View for the Group Page
  public function executeGroup(sfWebRequest $request)
  {
    $this->groups = Doctrine_Core::getTable('ProjectAllocation')
      ->createQuery('a')
      ->execute();
  }
}
