<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $majors->getId() ?></td>
    </tr>
    <tr>
      <th>Major:</th>
      <td><?php echo $majors->getMajor() ?></td>
    </tr>
    <tr>
      <th>Is visible:</th>
      <td><?php echo $majors->getIsVisible() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('major/edit?id='.$majors->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('major/index') ?>">List</a>