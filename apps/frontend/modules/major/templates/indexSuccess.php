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
    <?php foreach ($majors as $majors): ?>
    <tr>
      <td><a href="<?php echo url_for('major/show?id='.$majors->getId()) ?>"><?php echo $majors->getId() ?></a></td>
      <td><?php echo $majors->getMajor() ?></td>
      <td><?php echo $majors->getIsVisible() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('major/new') ?>">New</a>
