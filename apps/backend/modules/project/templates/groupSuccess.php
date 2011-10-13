<h1>Groups</h1>
<?php echo link_to('Generate New Project Allocations', 'group/allocate', 'confirm=Erase existing allocation and regenerate new ones?') ?><br><br>

<?php echo link_to('Edit Allocation', 'group/index') ?><br><br>

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

print('<table id="groupTable">');
print('<thead>');
print('<tr>');
print('<th>Group</th>');
print('<td>Students</td>');
print('</tr>');
print('</thead>');
print('<tbody>');
foreach ($allocs as $proj => $snums) {
	print('<tr>');
  	print("<th>" . $proj . "</th><td>");
  	print_r(implode(', ', $snums));
  	print('</td>');
  	print('</tr>');
}
print('</tbody>');
print('</table>');
?>
