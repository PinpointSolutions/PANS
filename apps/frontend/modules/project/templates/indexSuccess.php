<h1>Available Projects</h1>

<?php foreach ($projects as $project): ?>
  <div class="project">
    <div class="projectTitle">
    <a href="<?php echo url_for('project/show?id=' . $project->getId()) ?>" <?php /* target="_blank" */ ?> >
    <?php echo $project->getProjNum() ?>. <?php echo $project->getTitle() ?></a>
    </div>
    <table>
      <tr>
        <td>Client</td>
        <td>
          <div class="projectOrganisation"><?php echo $project->getOrganisation() ?></div>
        </td>
      </tr>
      <tr>
        <td>Suitable Major(s)</td>
        <td>
          <div class="projectMajors">(Majors)</div>
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
