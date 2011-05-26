<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $projects->getId() ?></td>
    </tr>
    <tr>
      <th>Title:</th>
      <td><?php echo $projects->getTitle() ?></td>
    </tr>
    <tr>
      <th>Organisation:</th>
      <td><?php echo $projects->getOrganisation() ?></td>
    </tr>
    <tr>
      <th>Description:</th>
      <td><?php echo $projects->getDescription() ?></td>
    </tr>
    <tr>
      <th>Has additional info:</th>
      <td><?php echo $projects->getHasAdditionalInfo() ?></td>
    </tr>
    <tr>
      <th>Major ids:</th>
      <td><?php echo $projects->getMajorIds() ?></td>
    </tr>
    <tr>
      <th>Skill set ids:</th>
      <td><?php echo $projects->getSkillSetIds() ?></td>
    </tr>
    <tr>
      <th>Nomination round:</th>
      <td><?php echo $projects->getNominationRound() ?></td>
    </tr>
    <tr>
      <th>Proj num:</th>
      <td><?php echo $projects->getProjNum() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('project/edit?id='.$projects->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('project/index') ?>">List</a>
