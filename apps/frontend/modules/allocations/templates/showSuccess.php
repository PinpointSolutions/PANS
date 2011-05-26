<table>
  <tbody>
    <tr>
      <th>Project:</th>
      <td><?php echo $project_allocations->getProjectId() ?></td>
    </tr>
    <tr>
      <th>Snum1:</th>
      <td><?php echo $project_allocations->getSnum1() ?></td>
    </tr>
    <tr>
      <th>Snum2:</th>
      <td><?php echo $project_allocations->getSnum2() ?></td>
    </tr>
    <tr>
      <th>Snum3:</th>
      <td><?php echo $project_allocations->getSnum3() ?></td>
    </tr>
    <tr>
      <th>Snum4:</th>
      <td><?php echo $project_allocations->getSnum4() ?></td>
    </tr>
    <tr>
      <th>Snum5:</th>
      <td><?php echo $project_allocations->getSnum5() ?></td>
    </tr>
    <tr>
      <th>Snum6:</th>
      <td><?php echo $project_allocations->getSnum6() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('allocations/edit?project_id='.$project_allocations->getProjectId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('allocations/index') ?>">List</a>
