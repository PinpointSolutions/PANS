<h1>Majors List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Title</th>
      <th>Organisation</th>
      <th>Description</th>
      <th>Has additional info</th>
      <th>Major ids</th>
      <th>Skill set ids</th>
      <th>Nomination round</th>
      <th>Proj num</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($majors as $projects): ?>
    <tr>
      <td><a href="<?php echo url_for('project/show?id='.$projects->getId()) ?>"><?php echo $projects->getId() ?></a></td>
      <td><?php echo $projects->getTitle() ?></td>
      <td><?php echo $projects->getOrganisation() ?></td>
      <td><?php echo $projects->getDescription() ?></td>
      <td><?php echo $projects->getHasAdditionalInfo() ?></td>
      <td><?php echo $projects->getMajorIds() ?></td>
      <td><?php echo $projects->getSkillSetIds() ?></td>
      <td><?php echo $projects->getNominationRound() ?></td>
      <td><?php echo $projects->getProjNum() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('project/new') ?>">New</a>
