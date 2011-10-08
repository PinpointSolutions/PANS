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
  public function executeSort(sfWebRequest $request)
  {
    // Load all the necessary data
    $this->students = Doctrine_Core::getTable('StudentUser')
      ->createQuery('a')
      ->execute();

    $this->students = Doctrine_Core::getTable('Project')
      ->createQuery('a')
      ->execute();
  }
}
