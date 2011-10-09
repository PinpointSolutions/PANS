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
      
      $desired[2000000] = $desired[$snum];
    }

    // Gather student groups that already exist
    $groups = array();
    foreach ($desired as $student => $others) {
      // Move on for now, if the student has no group
      if (sizeof($others) == 0) 
        continue;
      $this_group = array_merge(array($student), $others);

      // Iterate through any existing overlapping groups, and merge them
      // & is needed to modify the group in-place.
      for ($i = 0; $i < sizeof($groups); $i++) {
        $overlap = array_intersect($groups[$i], $this_group);
        if (sizeof($overlap) > 0) {
          $this_group = array_unique(array_merge($groups[$i], $this_group));
          $groups[$i] = array();
        }
      }
      array_push($groups, $this_group);
    }

    // Discard any empty arrays left from merging
    $new_groups = array();
    foreach($groups as $group) {
      if (sizeof($group) > 0)
        array_push($new_groups, $group);
    } 
    $groups = $new_groups;

    $this->groups = $groups;
    $this->desired = $desired;
    $this->undesired = $undesired;

    // $a = array(111, 222, 333, 444);
    // $b = array(100, 222, 444, 333, 555);
    // print_r($a + $b);
  }
}

