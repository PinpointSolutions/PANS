<h1>Allocations</h1>
<?php 
foreach ($allocations as $project => $group) {
    echo 'Project #'. $project . ' || ';
    foreach ($group as $student) {
        echo $student . ' | ';
    }
    echo '<br>';
} 
?>
<h1>Groups</h1>
<?php 
foreach ($groups as $group) {
    echo '| ';
    foreach ($group as $student)
        echo $student . ' | ';
    echo '<br>';
} 
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
?>