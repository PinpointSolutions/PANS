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

    // Grab student information for desired and undesired and student ids
    $desired = array();
    $undesired = array();
    $snums = array();
    foreach ($students as $student)
      $snums[$student->getSnum()] = $student;

    // Add dummy data for sorting testing
    for ($i = 0; $i < 5; $i++) {
      $num_desire = mt_rand(0, 5);
      $num_undesire = mt_rand(0, 5);
      $snum = array_rand($snums, 1);

      if ($num_desire > 1)
        $desired[$snum] = array_rand($snums, $num_desire);
      elseif ($num_desire == 1)
        $desired[$snum] = array(array_rand($snums, $num_desire));
      else
        $desired[$snum] = array();
      if ($num_undesire > 1)
        $undesired[$snum] = array_rand($snums, $num_undesire);
      elseif ($num_undesire == 1)
        $undesired[$snum] = array(array_rand($snums, $num_undesire));
      else
        $undesired[$snum] = array();
    }

    // Gather student groups that already exist
    $groups = array();
    foreach ($desired as $student => $others) {
      $this_group = array();
      if (sizeof($others) > 0) {
        $this_group += array($student);
        $this_group += $others;
      } else {
        continue; // Move on for now
        //$this_group = array($student);
      }

      $has_overlap = false;
      foreach ($groups as $group) {
        $overlap = array_intersect_assoc($group, $this_group);
        if (sizeof($overlap) > 0) {
          $group = array_merge($group, $this_group);
          $has_overlap = true;
        }
      }
      if ($has_overlap)
        continue;
      array_push($groups, $this_group);
    }

    $this->groups = print_r($groups, true);
    $this->desired = print_r($desired, true);
    $this->undesired = print_r($undesired, true);
  }
}
