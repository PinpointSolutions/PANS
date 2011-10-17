<h1>Groups</h1>

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
?>

<table id="groups">
<?php foreach ($allocs as $id => $alloc): ?>
  <tr>
    <td class="group_project" colspan="6"><?php echo $id . '. ' . $projects[$id]->getTitle(); ?></td>
  </tr>
  <tr>
  <?php foreach ($alloc as $s): ?>
    <td class="group_student"><?php echo $students[$s]->getFirstName() . ' ' . $students[$s]->getLastName() . '<br>(' . $s . ')'; ?></td>
  <?php endforeach; ?>
  </tr>
<?php endforeach; ?>
</table>

<?php echo link_to('Generate New Project Allocations', 'group/allocate', 'confirm=Erase existing allocation and regenerate new ones?') ?><br><br>

<?php echo link_to('Edit Allocation', 'group/index') ?><br><br>


