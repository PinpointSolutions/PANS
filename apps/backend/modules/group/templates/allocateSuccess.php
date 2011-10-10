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
<h1>Step 2: Split the graph (up to 6 for each group) using project preferences</h1>
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
echo 'Conflicted students (For Step 5): | ';
foreach ($singles as $s)
    echo $s . ' | ';
echo '<br><br>';
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
foreach ($conflict_free_allocation as $project => $group) {
    printf('Project %02d ====>', $project);
    foreach ($group as $student) {
        echo $student . '-->';
    }
    echo '<br>';
} 
echo '<br>';
?>
<h1>Step 6: Allocate single students with project preferences</h1>
<?php 
echo 'Unallocated students: <br> ';
foreach ($unallocated_students as $s)
    echo $s . ' | ';
echo '<br><br>';
foreach ($filled_allocation as $project => $group) {
    printf('Project %02d ====>', $project);
    foreach ($group as $student) {
        echo $student . '-->';
    }
    echo '<br>';
} 
echo '<br>';
echo 'Remaining unallocated students with either no preference or conflicts or no spots: <br>';
foreach ($no_choice_students as $s)
    echo $s . ' | ';
echo '<br><br>';
?>
<h1>Step 7: Allocate single students with no preferences, smallest group first</h1>
<?php 
foreach ($no_pref_allocation as $project => $group) {
    printf('Project %02d ====>', $project);
    foreach ($group as $student) {
        echo $student . '-->';
    }
    echo '<br>';
} 
echo '<br>';
echo 'Remaining unallocated students with conflicts or no spots: <br>';
foreach ($doomed_students as $s)
    echo $s . ' | ';
echo '<br><br>';
?>
<h1>Step 8: If there are still students left, assign them to brand new empty projects.</h1>
<?php 
foreach ($final_allocation as $project => $group) {
    printf('Project %02d ====>', $project);
    foreach ($group as $student) {
        echo $student . '-->';
    }
    echo '<br>';
} 
echo '<br><br>';
?>
<h1>Step 9: Optionally, if there are student still unallocated, it's because there's just no spot and everywhere is a conflict or something. Please assign these students manually.</h1>
<?php 
foreach ($alone_students as $s)
    echo $s . ' ';
echo '<br><br>';
?>
<h1>Done. Sanity check - if it's empty, it's good.</h1>
<?php 
foreach ($error as $e) {
    echo $e;
    echo '<br>';
}
echo '<br><br>'; ?>
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