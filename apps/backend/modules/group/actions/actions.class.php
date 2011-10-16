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
    $flags = array();
    foreach ($ps as $project)
      $projects[$project->getId()] = $project;

    // Grab student information for desired and undesired and student ids
    foreach ($ss as $student) {
      $flags[$student->getSnum()] = null;
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
    // for ($i = 0; $i < 16; $i++) {
    //   $n = mt_rand(0, 2);
    //   $m = mt_rand(0, 5);
    //   $num_desire = mt_rand(0, $n);
    //   $num_undesire = mt_rand(0, $m);
      
    //   $snum = 2000000 + $i;
    //   $pref = array_rand($projects, 5);
    //   shuffle($pref);

    //   if ($num_desire > 1)
    //     $desired[$snum] = array_rand($students, $num_desire);
    //   elseif ($num_desire == 1)
    //     $desired[$snum] = array(array_rand($students, $num_desire));
    //   else
    //     $desired[$snum] = array();

    //   if ($num_undesire > 1)
    //     $undesired[$snum] = array_rand($students, $num_undesire);
    //   elseif ($num_undesire == 1)
    //     $undesired[$snum] = array(array_rand($students, $num_undesire));
    //   else
    //     $undesired[$snum] = array();

    //   $prefs[$snum][0] = $pref[0];
    //   $prefs[$snum][1] = $pref[1];
    //   $prefs[$snum][2] = $pref[2];
    //   $prefs[$snum][3] = $pref[3];
    //   $prefs[$snum][4] = $pref[4];
    // }
    // for ($i = 0; $i < 100; $i++) {
    //   $snum = 2000000 + $i;
    //   $students[$snum]->setGpa(mt_rand(10, 70) / 10.0);
    //   $students[$snum]->setDegreeIds(mt_rand(1, 3));
    // }
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
    $this->singles = $singles;

    // Assign groups to projects
    foreach ($groups as $group) {
      $allocations = $this->assignGroup($group, $allocations, $students, $projects, $prefs);
      $finished = array_merge($finished, $group);
    }
    ksort($allocations);
    $this->initial_allocation = $allocations;

    // Handles conflicted students due to undesired list
    $singles_tmp = $singles;
    foreach ($singles_tmp as $single) {
      $new_allocations = $this->tryAssignOne($single, $allocations, $projects, $prefs, $undesired);
      if (!$new_allocations) {
        continue;
      } else {
        $allocations = $new_allocations;
        $singles = array_diff($singles, array($single));
      }
    }
    ksort($allocations);
    $this->conflict_free_allocation = $allocations;

    // Single-student handling.  After this point, allocations should be append-only
    $unallocated_students = $this->findAllUnallocatedStudents($allocations, $students);
    shuffle($unallocated_students);
    $this->unallocated_students = $unallocated_students;
    // Then, for each unallocated students, try to assign them
    foreach ($unallocated_students as $us) {
      $new_allocations = $this->tryAssignOne($us, $allocations, $projects, $prefs, $undesired);
      if (!$new_allocations) {
        continue;
      } else {
        $allocations = $new_allocations;
        $unallocated_students = array_diff($unallocated_students, array($us));
      }
    }
    ksort($allocations);
    $this->filled_allocation = $allocations;
    $this->no_choice_students = $unallocated_students;

    // Okay, we have people who have no preferences what-so-ever.  Go through the groups
    // and put them into open spots, with smaller groups first
    shuffle($unallocated_students);
    foreach ($unallocated_students as $us) {
      $new_allocations = $this->tryAssignNoPreference($us, $allocations, $students, $projects, $undesired);
      if (!$new_allocations) {
        continue;
      } else {
        $allocations = $new_allocations;
        $unallocated_students = array_diff($unallocated_students, array($us));
      }
    }
    //ksort($allocations);
    $this->no_pref_allocation = $allocations;
    $this->doomed_students = $unallocated_students;

    // If there are _still_ students left, it's because the majority had no
    // real preference, or if the majority had preference but they all want
    // the same project.  In this case, preferences don't work anymore, and
    // we fallback to group balance and rating.
    $allocations = $this->tryAssignNewGroup($unallocated_students, $allocations, $students, $projects, $undesired);
    ksort($allocations);
    $this->final_allocation = $allocations;
    $unallocated_students = $this->findAllUnallocatedStudents($allocations, $students);
    $this->alone_students = $unallocated_students;

    // It's done, but just to be sure ...
    // Sanity checks
    $this->error = array();

    // Sanity check - ensure the remaining doomed students with no groups is the same people
    // that we can examine and count and find out
    $real_unallocated_students = $this->findAllUnallocatedStudents($allocations, $students);
    if (sizeof($real_unallocated_students) > 0) {
      sort($unallocated_students);
      sort($real_unallocated_students);
      if ($unallocated_students != $real_unallocated_students) {
        $this->error[] = 'ERROR: Unallocated student(s) missed by the system: ' .
          print_r(array_diff($real_unallocated_students, $unallocated_students), true); 
      }
    }

    // Sanity check - total number of allocated students is the same as the number of
    // students we had to begin with
    if (sizeof($real_unallocated_students) == 0 && 
        sizeof($students) != sizeof($allocations, 1) - sizeof($allocations)) {
      $this->error[] = 'ERROR: No leftovers but allocated students and total number of students do not match: Actual number: ' . sizeof($students) . ' Allocated total: ' . (sizeof($allocations, 1) - sizeof($allocations));
    }

    // Output to debug dump
    ksort($allocations);
    $this->prefs = $prefs;
    $this->allocations = $allocations;
    $this->desired = $desired;
    $this->undesired = $undesired;

    // Rewrite to database
    $conn = Doctrine_Manager::getInstance();
    $groups = Doctrine_Core::getTable('ProjectAllocation')->findAll();
    $groups->delete();

    $this->allocation_collection = new Doctrine_Collection('ProjectAllocation');
    foreach ($allocations as $project => $group) {
      foreach ($group as $student) {
        $item = new ProjectAllocation();
        $item->setProjectId($project);
        $item->setSnum($student);
        $this->allocation_collection->add($item);
      }
    }
    $this->allocation_collection->save();
  }


  // Returns a list of preferred projects by the group with most desired as the first one
  protected function getGroupPreference($group, $projects, $prefs)
  {
    $pref_count = array();
    foreach ($projects as $id => $project)
      $pref_count[$id] = 0.0;

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
    return $pref_count;
  }


  // Assigns group to project.  Returns a new allocation.
  protected function assignGroup($group, $allocations, $students, $projects, $prefs)
  {
    $pref_count = $this->getGroupPreference($group, $projects, $prefs);
    // Allocate the group by the preferences
    foreach ($pref_count as $pref => $_) {
      if ($projects[$pref]->getMaxGroupSize() < sizeof($group))
        continue;
      if (array_key_exists($pref, $allocations) && $allocations[$pref] != $group) {
        $group1 = $this->rateGroup($allocations[$pref], $students, $prefs);
        $group2 = $this->rateGroup($group, $students, $prefs);
        if ($group1 >= $group2) {
          continue;
        } else {
          // If we find a group with better match, swap and reassign the first.
          $allocations[$pref] = $group;
          return $this->assignGroup($allocations[$pref], $allocations, $students, $projects, $prefs);
        }
      }
      $allocations[$pref] = $group;
      break;
    }
    return $allocations;
  }


  // Attempts to assign a single student (assuming unallocated) to a group by:
  //    Project preference
  //    Having no other people undesiring him/her
  //    Group has open spot
  // Desired students are not used here because it is very unlikely that the
  // person would be allocated there, since he/she was probably kicked out.
  // Returns the new allocation if success.  If not, returns null.
  protected function tryAssignOne($student, $allocations, $projects, $prefs, $undesired)
  {
    // Go through each one of the student's project preferences
    foreach ($prefs[$student] as $pref) {
      if ($pref == -1)
        continue;
      // If the group doesn't exist yet, and the student wants this project,
      // let's start one!
      if (!array_key_exists($pref, $allocations)) { 
        $allocations[$pref] = array($student);
        return $allocations;
      }
      // A group exists in the student's preference. Check for an open spot.
      if (sizeof($allocations[$pref]) >= $projects[$pref]->getMaxGroupSize())
        continue;
      // Check for conflicts
      foreach ($allocations[$pref] as $allocated_student) 
        if (in_array($student, $undesired[$allocated_student]))
          continue 2;
      // Okay, security scan passed. You can now enter the terminal and board your group.
      $allocations[$pref] = array_merge($allocations[$pref], array($student));
        return $allocations;
    }
    // If we get to this point, it means no project could be assigned to the student's
    // preference. Too bad.
    return null;
  }


  // Similar to the function above, but does a best-fit algorithm instead.  Smaller groups
  // have higher preferences in this section.
  protected function tryAssignNoPreference($student, $allocations, $students, $projects, $undesired)
  {
    // Sort the allocations by group size, with smallest group first
    uasort($allocations, function ($a, $b) {
      return sizeof($a) - sizeof($b);
    });
    foreach ($allocations as $project => $group) {
      // Skip groups with no openings
      if ($projects[$project]->getMaxGroupSize() <= sizeof($group))
        continue;
      // Skip if this student is undesired by at least one person in the group
      foreach ($group as $allocated_student)
        if (in_array($student, $undesired[$allocated_student]))
          continue 2;
      // Skip if this student's form isn't completed.
      if ($students[$student]->getFormCompleted() == false)
        continue;
      // Okay, you can probably join us.
      $allocations[$project] = array_merge($allocations[$project], array($student));
      return $allocations;
    }
    // Not very likely to happen, but if all groups are either filled or hate this person,
    // derp-de-derp.
    return null;
  }


  // Similar to the function above, but looks for a good grouop balance instead.
  // Takes a list of unallocated students instead of just one student, and we also
  // only look at empty projects, since that's where the only real spots left.
  // Return the new allocation.
  protected function tryAssignNewGroup($unallocated, $allocations, $students, $projects, $undesired)
  {
    // Open up projects with no one at all
    foreach ($projects as $project => $_) {
      if (!array_key_exists($project, $allocations))
        $allocations[$project] = array();
    }

    // For degree balance, try to sort the unallocated students into their own degrees
    // Then we interleave them for balance.  This code assumes a similar number of 
    // students in each degree.
    $degrees = array();
    foreach ($unallocated as $student) {
      $degree_id = $students[$student]->getDegreeIds();
      if ($degree_id == null)
        $degree_id = 0;
      if (array_key_exists($degree_id, $degrees))
        $degrees[$degree_id][] = $student;
      else
        $degrees[$degree_id] = array($student);
    }
    $interleaved = array();
    while (sizeof($degrees, 1) - sizeof($degrees) != 0) {
      for ($i = 0; $i < sizeof($degrees); $i++) {
        if (!array_key_exists($i, $degrees) || sizeof($degrees[$i]) == 0)
          continue;
        $s = array_pop($degrees[$i]);
        $interleaved[] = $s;
      }
    }

    // Insert these people into the projects randomly, pretty much. 
    $empty_projects = array();
    foreach (array_keys($allocations) as $project)
      if (sizeof($allocations[$project]) == 0)
        $empty_projects[] = $project;

    shuffle($empty_projects);
    foreach ($empty_projects as $project) {
      if (sizeof($interleaved) == 0)
        break;
      $this_group = array();
      for ($i = 0; $i < $projects[$project]->getMaxGroupSize(); $i++) {
        if (sizeof($interleaved) == 0)
          break;
        $this_group[] = array_pop($interleaved);
      }
      $allocations[$project] = $this_group;
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
        $this_group = array_unique(array_merge(array($student), $others));
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


  // We gotta figure out who hasn't been allocated yet.
  protected function findAllUnallocatedStudents($allocations, $students) 
  {
    $unallocated_students = array();
    foreach ($students as $snum => $_) {
      foreach ($allocations as $_ => $group) {
        if (in_array($snum, $group))
          continue 2;
      }
      $unallocated_students[] = $snum;
    }
    return $unallocated_students;
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
    $degree_score = sizeof($degrees) * 7.0 / 3.0;

    $student_prefs = array();
    foreach ($group as $student) {
      if ($prefs[$student][0] == -1)
        continue;
      $student_prefs[] = $prefs[$student][0];
    }
    $student_prefs = array_unique($prefs);
    $prefs_score = 7.0 / sizeof($student_prefs);

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
      // if ($pref == -1)
      //   continue;
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
    if (!$this->hasTooMuchLeftover($groups))
      return $groups;

    $limit = sizeof($groups, 1);
    $new_groups = array();
    while ($this->hasTooMuchLeftover($groups) && $limit > 0) {
      $leftovers = array();
      foreach ($groups as $group) {
        $shaved = $this->shaveGroup($group, $students, $prefs);
        $new_groups[] = $shaved;
        $leftover = array_diff($group, $shaved);
        if (sizeof($leftover) > 0)
          $leftovers[] = $leftover;
      }
      $groups = $leftovers;
      $limit--;
    }
    return $new_groups;
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

