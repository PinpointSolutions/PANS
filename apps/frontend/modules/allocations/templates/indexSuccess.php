<h1>Project allocationss List</h1>

<table>
  <thead>
    <tr>
      <th>Project</th>
      <th>Snum1</th>
      <th>Snum2</th>
      <th>Snum3</th>
      <th>Snum4</th>
      <th>Snum5</th>
      <th>Snum6</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($project_allocationss as $project_allocations): ?>
    <tr>
      <td><a href="<?php echo url_for('allocations/show?project_id='.$project_allocations->getProjectId()) ?>"><?php echo $project_allocations->getProjectId() ?></a></td>
      <td><?php echo $project_allocations->getSnum1() ?></td>
      <td><?php echo $project_allocations->getSnum2() ?></td>
      <td><?php echo $project_allocations->getSnum3() ?></td>
      <td><?php echo $project_allocations->getSnum4() ?></td>
      <td><?php echo $project_allocations->getSnum5() ?></td>
      <td><?php echo $project_allocations->getSnum6() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('allocations/new') ?>">New</a>
