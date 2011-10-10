<h1>Groups</h1>

<?php echo link_to('Edit Allocation', 'group/index') ?><br><br>

<?php echo link_to('Generate New Project Allocations', 'group/allocate') ?><br><br>

<?php 
$allocs = array();
foreach ($groups as $group) { 
    $proj = $group->getProjectId();
    $stud = $group->getSnum();
    if (array_key_exists($proj, $allocs))
        array_push($allocs[$proj], $stud);
    else
        $allocs[$proj] = array($stud);
   
    print("group #" . $proj . " = ");
    print_r($allocs[$proj]);
        
    echo('<br><br>');
} ?>
