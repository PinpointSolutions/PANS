<h1>Groups</h1>

<?php foreach ($groups as $group): ?>

  
  <?php echo $group->getId(); ?>: <?php echo $group->getProjectId(); ?> - <?php echo $group->getSnum(); ?>
  <br>

<?php endforeach; ?>