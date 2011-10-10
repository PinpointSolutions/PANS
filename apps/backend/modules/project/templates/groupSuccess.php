<h1>Groups</h1>

<?php echo link_to('Edit Allocation', 'group/index') ?><br><br>

<?php echo link_to('Generate New Project Allocations', 'group/allocate') ?><br><br>

<?php 
$allocs = array();
foreach ($groups as $group) { 
    $proj = $group->getProjectId();
    $stud = $group->getSnum();
    if (array_key_exists($proj, $allocs))
        $allocs[$proj][] = $stud;
    else
        $allocs[$proj] = array($stud);
} 

foreach ($allocs as $proj => $snums) {
  print("group #" . $proj . " = ");
  print_r(implode(', ', $snums));
  echo('<br><br>');
}

?>
