<table>
  <tbody>
    <tr>
      <th>Username:</th>
      <td><?php echo $admin_users->getUsername() ?></td>
    </tr>
    <tr>
      <th>P word:</th>
      <td><?php echo $admin_users->getPWord() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('job/edit?username='.$admin_users->getUsername()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('job/index') ?>">List</a>
