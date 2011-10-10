<h1>Project Allocation Debug Dump</h1>

<h1>Final Allocation :D</h1>
<?php 
foreach ($allocations as $project => $group) {
    printf('Project %02d ====>', $project);
    foreach ($group as $student) {
        echo $student . '-->';
    }
    echo '<br>';
} 
echo '<br>';
?>
<h1>Step 1: Connected graph of all students with desired students</h1>
<?php 
foreach ($groups as $group) {
    echo '|---->';
    foreach ($group as $student)
        echo $student . '-->';
    echo '<br>';
} 
echo '<br>';
?>
<h1>Step 2: Split the graph (max group size: 6) using common project preferences</h1>
<?php 
foreach ($shaved_groups as $group) {
    echo '|----> ';
    foreach ($group as $student)
        echo $student . '-->';
    echo '<br>';
} 
echo '<br>';
?>
<h1>Step 3: Remove students by undesired students using group balance</h1>
<?php 
foreach ($conflict_free_groups as $group) {
    echo '|----> ';
    foreach ($group as $student)
        echo $student . '-->';
    echo '<br>';
} 
echo '<br>';
?>
<h1>Step 4: Initial group-based project allocation by group preference</h1>
<?php 
foreach ($initial_allocation as $project => $group) {
    printf('Project %02d ====>', $project);
    foreach ($group as $student) {
        echo $student . '-->';
    }
    echo '<br>';
} 
echo '<br>';
?>
<h1>Step 5: Allocate conflicted students in pre-formed groups</h1>
<?php 
echo 'Conflicted students: | ';
foreach ($singles as $s)
    echo $s . ' | ';
echo '<br>';
foreach ($conflict_free_allocation as $project => $group) {
    printf('Project %02d ====>', $project);
    foreach ($group as $student) {
        echo $student . '-->';
    }
    echo '<br>';
} 
echo '<br>';

?>
<h1>Input: Desired</h1>
<?php 
foreach ($desired as $student => $others) {
    echo $student . ' || ';
    foreach ($others as $other) {
        echo $other . ' | ';
    }
    echo '<br>';
} 
echo '<br>';
?>
<br>
<h1>Input: Project Preferences</h1>
<?php 
foreach ($prefs as $student => $projects) {
    echo $student . ' || ';
    foreach ($projects as $p) {
        echo $p . ' | ';
    }
    echo '<br>';
} 
echo '<br>';
?>
<br>
<h1>Input: Undesired</h1>
<?php 
foreach ($undesired as $student => $others) {
    echo $student . ' || ';
    foreach ($others as $other) {
        echo $other . ' | ';
    }
    echo '<br>';
} 
echo '<br>';
?>