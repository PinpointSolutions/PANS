<h1>Available Projects</h1>

<?php foreach ($projects as $project): ?>
  <div class="project">
    <div class="projectTitle">
      <?php echo $project; ?>
    </div>
    <table>
      <tr>
        <td>Client</td>
        <td>
          <div class="projectOrganisation"><?php echo $project->getOrganisation() ?></div>
        </td>
      </tr>
      <tr>
        <td>Suitable Degree(s)</td>
        <td>
          <div class="projectDegrees">
            <?php $degreeIds=explode(' ', $project->getDegreeIds()); ?>
            <?php $degrees=array(); ?>
            <?php foreach ($degreeIds as $id): ?>
              <?php array_push($degrees, $degreeName[$id]); ?>
            <?php endforeach; ?>
            <?php echo implode(', ', $degrees);?>
          </div>
        </td>
      </tr>
      <tr>
        <td>Suitable Major(s)</td>
        <td>
          <div class="projectMajors">
            <?php $majorIds=explode(' ', $project->getMajorIds()); ?>
            <?php $majors=array(); ?>
            <?php foreach ($majorIds as $id): ?>
              <?php array_push($majors, $majorName[$id]); ?>
            <?php endforeach; ?>
            <?php echo implode(', ', $majors);?>
          </div>
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
          <div class="projectNotes"><?php if ($project->getHasAdditionalInfo() == 1) { echo "Additional information available upon request."; } ?></div>
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
          <div class="projectDescription"><?php echo $project->getDescription() ?></div>
        </td>
      </tr>
    </table>
  </div>
<?php endforeach; ?>
