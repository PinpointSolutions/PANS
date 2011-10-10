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
    $ss = Doctrine_Core::getTable('StudentUser')
      ->createQuery('a')
      ->execute();
    
    $ps = Doctrine_Core::getTable('Project')
      ->createQuery('a')
      ->execute();

    // Internal data initialisation
    $allocations = array();
    $finished = array();
    $projects = array();
    $desired = array();
    $undesired = array();
    $prefs = array();
    $students = array();
    $singles = array();
    foreach ($ps as $project)
      $projects[$project->getId()] = $project;

    // Grab student information for desired and undesired and student ids
    foreach ($ss as $student) {
      // FIXME: Discount any students whose forms are not completed.
      // if ($student->getFormCompleted == 0)
      //   continue;
      $students[$student->getSnum()] = $student;
      $desired[$student->getSnum()] = array_unique(array($student->getYStuPref1(),
                                                         $student->getYStuPref2(),
                                                         $student->getYStuPref3(),
                                                         $student->getYStuPref4(),
                                                         $student->getYStuPref5()));
      $desired[$student->getSnum()] = array_diff(
                                        $desired[$student->getSnum()], array(null));
      
      $undesired[$student->getSnum()] = array_unique(array($student->getNStuPref1(),
                                                           $student->getNStuPref2(),
                                                           $student->getNStuPref3(),
                                                           $student->getNStuPref4(),
                                                           $student->getNStuPref5()));
      $undesired[$student->getSnum()] = array_diff(
                                          $undesired[$student->getSnum()], array(null));

      // Buffer for student preferences.  For null fields, we use -1 throughout.
      $prefs[$student->getSnum()] = array($student->getProjPref1(),
                                          $student->getProjPref2(),
                                          $student->getProjPref3(),
                                          $student->getProjPref4(),
                                          $student->getProjPref5());
      for ($i = 0; $i < 5; $i++) {
        if ($prefs[$student->getSnum()][$i] == null)
          $prefs[$student->getSnum()][$i] = -1;
      }
    }
    
    // DELETEME: Add dummy data for sorting testing
    for ($i = 0; $i < 96; $i++) {
      $n = mt_rand(0, 3);
      $m = mt_rand(0, 3);
      $num_desire = mt_rand(0, $n);
      $num_undesire = mt_rand(0, $m);
      
      $snum = 2000000 + $i;
      $pref = array_rand($projects, 5);
      shuffle($pref);

      if ($num_desire > 1)
        $desired[$snum] = array_rand($students, $num_desire);
      elseif ($num_desire == 1)
        $desired[$snum] = array(array_rand($students, $num_desire));
      else
        $desired[$snum] = array();

      if ($num_undesire > 1)
        $undesired[$snum] = array_rand($students, $num_undesire);
      elseif ($num_undesire == 1)
        $undesired[$snum] = array(array_rand($students, $num_undesire));
      else
        $undesired[$snum] = array();

      $prefs[$snum][0] = $pref[0];
      $prefs[$snum][1] = $pref[1];
      $prefs[$snum][2] = $pref[2];
      $prefs[$snum][3] = $pref[3];
      $prefs[$snum][4] = $pref[4];
      $students[$snum]->setGpa(mt_rand(0, 70) / 10.0);
    }
    // END_DELETEME

    // Figure out the big groups
    $groups = $this->combineDesiredStudents($desired);
    $this->groups = $groups;

    // Shave off groups that are too big
    // Split really big groups.
    $groups = $this->shaveMultipleGroups($groups, $students, $prefs);
    $this->shaved_groups = $groups;

    // Check for initial group conflicts
    $conflict = $this->resolveUndesiredStudents($groups, $undesired, $students, $prefs);
    $singles = $conflict[1];
    $groups = $conflict[0];
    $this->conflict_free_groups = $groups;

    // Assign groups to projects
    foreach ($groups as $group) {
      $allocations = $this->assignGroup($group, $allocations, $students, $projects, $prefs);
      $finished = array_merge($finished, $group);
    }
    ksort($allocations);
    $this->initial_allocation = $allocations;


    // Output to debug dump
    $this->prefs = $prefs;
    $this->allocations = $allocations;
    $this->desired = $desired;
    $this->undesired = $undesired;
    $this->singles = $singles;
  }


  // Assigns group to project.  Returns a new allocation.
  protected function assignGroup($group, $allocations, $students, $projects, $prefs)
  {
    $pref_count = array();
    for ($i = 1; $i <= sizeof($projects); $i++)
      $pref_count[$i] = 0.0;

    // Calculate the most desired projects in order
    foreach ($group as $student) {
      $pref1 = $prefs[$student][0];
      $pref2 = $prefs[$student][1];
      $pref3 = $prefs[$student][2];
      $pref4 = $prefs[$student][3];
      $pref5 = $prefs[$student][4];

      // These ratings are arbitrary, but shouldn't change too much of the results.
      if ($pref1 != -1)
        $pref_count[$pref1] += 5.0;
      if ($pref2 != -1)
        $pref_count[$pref2] += 4.5;
      if ($pref3 != -1)
        $pref_count[$pref3] += 4.0;
      if ($pref4 != -1)
        $pref_count[$pref4] += 3.5;
      if ($pref5 != -1)
        $pref_count[$pref5] += 3.0;
    }
    arsort($pref_count);

    // Allocate the group
    foreach ($pref_count as $pref => $p) {
      if (array_key_exists($pref, $allocations))
        continue;
      $allocations[$pref] = $group;
      return $allocations;
    }
    return $allocations;
  }


  // Combines desired students into a bigger group if they overlap.
  protected function combineDesiredStudents($desired)
  {
    $groups = array();
    foreach ($desired as $student => $others) {
      // Make one group for the student and his/her desired fellows
      $this_group = null;
      if (sizeof($others) == 0) {
        $this_group = array($student);
      } else {
        $this_group = array_merge(array($student), $others);
      }

      // Iterate through any existing overlapping groups, and merge them
      // & is needed to modify the group in-place.
      for ($i = 0; $i < sizeof($groups); $i++) {
        $overlap = array_intersect($groups[$i], $this_group);
        if (sizeof($overlap) > 0) {
          $this_group = array_unique(array_merge($groups[$i], $this_group));
          $groups[$i] = array();
        }
      }
      $groups[] = $this_group;
    }

    // Discard any empty arrays left from merging, also discard single-student groups
    $new_groups = array();
    foreach($groups as $group) {
      if (sizeof($group) > 1)
        $new_groups[] = $group;
    } 
    return $new_groups;
  }


  // Remove students with conflicts based on group ratings
  // Returns an array of two arrays:
  //  array( [0] => Conflict-free groups,
  //         [1] => Kicked-out students =[ )
  protected function resolveUndesiredStudents($groups, $undesired, $students, $prefs)
  {
    $output = array(array(), array());
    foreach ($groups as $group) {
      $black_sheep = $this->checkGroup($group, $undesired, $students, $prefs);
      $new_group = $group;
      while ($black_sheep != null) {
        $new_group = array_diff($new_group, array($black_sheep));
        $output[1][] = $black_sheep;
        $black_sheep = $this->checkGroup($new_group, $undesired, $students, $prefs);
      }
      $output[0][] = $new_group;
    }
    return $output;
  }


  // For one group, check for an undesired student inside the same group.
  // If we have one, kick one out and check the group dynamics.  Kick the other
  // out and check again.
  // If the student puts in himself/herself here, bad things will happen.  For example,
  // we will totally disregard such craziness.
  // This function returns the student to be removed, or null if the group is 
  // conflict-free.
  protected function checkGroup($group, $undesired, $students, $prefs)
  {
    foreach ($group as $student) {
      foreach ($undesired[$student] as $us) {
        if (in_array($us, $group) && $student != $us) {
          $option1 = array_diff($group, array($student));
          $option2 = array_diff($group, array($us));
          if ($this->rateGroup($option1, $students, $prefs) 
              >= $this->rateGroup($option2, $students, $prefs))
            return $us;
          else
            return $student;
        }
      }
    }
    return null;
  }


  // Gives a numerical rating of the group based on:
  //   Group GPA average
  //   Balance of degrees
  //   Similarity of project preferences
  protected function rateGroup($group, $students, $prefs)
  {
    $gpa_score = 0.0;
    foreach ($group as $student)
      $gpa_score += $students[$student]->getGpa();
    $gpa_score /= sizeof($group);

    $degrees = array();
    foreach ($group as $student)
      $degrees = array_merge($degrees, 
                    explode(' ', $students[$student]->getDegreeIds()));
    $degrees = array_unique($degrees);
    $degree_score = sizeof($degrees);

    $student_prefs = array();
    foreach ($group as $student) {
      if ($prefs[$student][0] == -1)
        continue;
      $student_prefs[] = $prefs[$student][0];
    }
    $student_prefs = array_unique($prefs);
    $prefs_score = 5.0 / sizeof($student_prefs);

    return $gpa_score + $degree_score + $prefs_score;
  }


  // Ensure the group is not oversized.  Returns a group smaller or equal in size.
  protected function shaveGroup($group, $students, $prefs)
  {
    if (sizeof($group) <= 6) {
      return $group;
    }
    
    // Group is too big.  Let's look at project preferences.
    // This step is skipped as long as one person has a different 1st project
    // preference at the moment, however it's easy to expand this further
    $student_prefs = array();
    foreach ($group as $student) {
      $pref = $prefs[$student][0];
      if ($pref == -1)
        continue;
      if (array_key_exists($pref, $student_prefs))
        $student_prefs[$pref][] = $student;
      else
        $student_prefs[$pref] = array($student);
    }
    if (sizeof($student_prefs) > 1) {
      $max = 0;
      $max_index = -1;
      foreach ($student_prefs as $project => $snums) {
        if (sizeof($snums) > $max) {
          $max = sizeof($snums);
          $max_index = $project;
        }
      }
      return $this->shaveGroup($student_prefs[$max_index], $students, $prefs);
    }

    // Okay, everyone has the same first preference.  Let's look at their GPA.
    $gpas = array();
    foreach ($group as $student) {
      $gpa = $students[$student]->getGpa();
      if (!$gpa)
        $gpa = 0.0;
      $gpas[$student] = $gpa;
    }
    arsort($gpas);
    return array_keys(array_slice($gpas, 0, 6, true));
  }


  // Split big groups into multiple groups
  protected function shaveMultipleGroups($groups, $students, $prefs)
  {
    $limit = sizeof($groups);
    $groups_tmp = array();
    while ($this->hasTooMuchLeftover($groups) && $limit > 0) {
      $leftovers = array();
      foreach ($groups as $group) {
        $shaved = $this->shaveGroup($group, $students, $prefs);
        $groups_tmp[] = $shaved;
        $leftover = array_diff($group, $shaved);
        if (sizeof($leftover) > 0)
          $leftovers[] = $leftover;
      }
      $groups = $leftovers;
      $limit--;
    }
    return $groups_tmp;
  }


  // Helper function for shaveMultipleGroups
  protected function hasTooMuchLeftover($leftovers)
  {
    foreach ($leftovers as $leftover) {
      if (sizeof($leftover) > 6)
        return true;
    }
    return false;
  }
}

