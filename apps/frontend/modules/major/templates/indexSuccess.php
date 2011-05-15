<h1>Majors List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Major</th>
      <th>Is visible</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($Majors as $Major): ?>
    <tr>
      <td><a href="<?php echo url_for('major/show?id='.$Major->getId()) ?>"><?php echo $Major->getId() ?></a></td>
      <td><?php echo $Major->getMajor() ?></td>
      <td><?php echo $Major->getIsVisible() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('major/new') ?>">New</a>
