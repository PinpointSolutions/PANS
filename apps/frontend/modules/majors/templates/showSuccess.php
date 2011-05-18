<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $Major->getId() ?></td>
    </tr>
    <tr>
      <th>Major:</th>
      <td><?php echo $Major->getMajor() ?></td>
    </tr>
    <tr>
      <th>Is visible:</th>
      <td><?php echo $Major->getIsVisible() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('majors/edit?id='.$Major->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('majors/index') ?>">List</a>
