<h1>Admin userss List</h1>

<table>
  <thead>
    <tr>
      <th>Username</th>
      <th>P word</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($admin_userss as $admin_users): ?>
    <tr>
      <td><a href="<?php echo url_for('job/show?username='.$admin_users->getUsername()) ?>"><?php echo $admin_users->getUsername() ?></a></td>
      <td><?php echo $admin_users->getPWord() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('job/new') ?>">New</a>
