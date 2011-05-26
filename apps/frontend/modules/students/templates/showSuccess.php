<?php slot(
  'title',
  sprintf('%s Edit Student User %s', $student_users->getSnum(), $student_users->getLastName()))
?>
<table>
  <tbody>
    <tr>
      <th>Snum:</th>
      <td><?php echo $student_users->getSnum() ?></td>
    </tr>
    <tr>
      <th>P word:</th>
      <td><?php echo $student_users->getPWord() ?></td>
    </tr>
    <tr>
      <th>First name:</th>
      <td><?php echo $student_users->getFirstName() ?></td>
    </tr>
    <tr>
      <th>Last name:</th>
      <td><?php echo $student_users->getLastName() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('students/edit?snum='.$student_users->getSnum()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('students/index') ?>">List</a>
