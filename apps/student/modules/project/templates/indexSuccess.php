<h1>Available Projects</h1>

<?php foreach ($projects as $project): ?>
  <div class="project">
    <div class="projectTitle"><a href="#"><?php echo $project->getProjNum() ?>. <?php echo $project->getTitle() ?></a></div>
    <div class="projectOrganisation"><?php echo $project->getOrganisation() ?></div>
    <div class="projectMajors">(Majors)</div>
    <div class="projectNotes"><?php if ($project->getHasAdditionalInfo() == 1) { echo "Additional information available upon request."; } ?></div>
    <div class="projectDescription"><?php echo $project->getDescription() ?></div>
  </div>
<?php endforeach; ?>

<div class="action"><a href="<?php echo "#" ?>">Nomination Form</a></div>