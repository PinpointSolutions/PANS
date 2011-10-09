<h1>Result</h1>
<?php 
foreach ($groups as $group) {
    echo '| ';
    foreach ($group as $student) {
        echo $student . ' | ';
    }
    echo '<br>';
} 
?>
<h1>Desired</h1>
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
<h1>Undesired</h1>
<?php 
foreach ($undesired as $student => $others) {
    echo $student . ' || ';
    foreach ($others as $other) {
        echo $other . ' | ';
    }
    echo '<br>';
} 
?>