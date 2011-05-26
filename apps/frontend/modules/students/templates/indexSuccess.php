<h1>Student userss List</h1>

<table>
  <thead>
    <tr>
      <th>Snum</th>
      <th>P word</th>
      <th>First name</th>
      <th>Last name</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($student_userss as $student_users): ?>
    <tr>
      <td><a href="<?php echo url_for('students/show?snum='.$student_users->getSnum()) ?>"><?php echo $student_users->getSnum() ?></a></td>
      <td><?php echo $student_users->getPWord() ?></td>
      <td><?php echo $student_users->getFirstName() ?></td>
      <td><?php echo $student_users->getLastName() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('students/new') ?>">New</a>
