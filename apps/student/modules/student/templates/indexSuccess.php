<h1>Student List</h1>

<table>
  <thead>
    <tr>
      <th>Student Number</th>
      <th>First Name</th>
      <th>Last Name</th>
      <?php /*
      <th>Pass fail pm</th>
      <th>Major ids</th>
      <th>Gpa</th>
      <th>Proj pref1</th>
      <th>Proj pref2</th>
      <th>Proj pref3</th>
      <th>Proj pref4</th>
      <th>Proj pref5</th>
      <th>Skill set ids</th>
      <th>Preferred Student 1</th>
      <th>Preferred Student 2</th>
      <th>Preferred Student 3</th>
      <th>Preferred Student 4</th>
      <th>Preferred Student 5</th>
      <th>N stu pref1</th>
      <th>N stu pref2</th>
      <th>N stu pref3</th>
      <th>N stu pref4</th>
      <th>N stu pref5</th>
      <th>Proj just1</th>
      <th>Proj just2</th>
      <th>Proj just3</th>
      <th>Proj just4</th>
      <th>Proj just5</th>
      */ ?>
      <th>Completed</th>
      <th>Last Modified</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($student_users as $student_user): ?>
    <tr>
      <td><a href="<?php echo url_for('student/show') ?>"><?php echo $student_user->getSnum() ?></a></td>
      <td><?php echo $student_user->getFirstName() ?></td>
      <td><?php echo $student_user->getLastName() ?></td>
      <?php /*
      <td><?php echo $student_user->getPassFailPm() ?></td>
      <td><?php echo $student_user->getMajorIds() ?></td>
      <td><?php echo $student_user->getGpa() ?></td>
      <td><?php echo $student_user->getProjPref1() ?></td>
      <td><?php echo $student_user->getProjPref2() ?></td>
      <td><?php echo $student_user->getProjPref3() ?></td>
      <td><?php echo $student_user->getProjPref4() ?></td>
      <td><?php echo $student_user->getProjPref5() ?></td>
      <td><?php echo $student_user->getSkillSetIds() ?></td>
      <td><?php echo $student_user->getYStuPref1() ?></td>
      <td><?php echo $student_user->getYStuPref2() ?></td>
      <td><?php echo $student_user->getYStuPref3() ?></td>
      <td><?php echo $student_user->getYStuPref4() ?></td>
      <td><?php echo $student_user->getYStuPref5() ?></td>
      <td><?php echo $student_user->getNStuPref1() ?></td>
      <td><?php echo $student_user->getNStuPref2() ?></td>
      <td><?php echo $student_user->getNStuPref3() ?></td>
      <td><?php echo $student_user->getNStuPref4() ?></td>
      <td><?php echo $student_user->getNStuPref5() ?></td>
      <td><?php echo $student_user->getProjJust1() ?></td>
      <td><?php echo $student_user->getProjJust2() ?></td>
      <td><?php echo $student_user->getProjJust3() ?></td>
      <td><?php echo $student_user->getProjJust4() ?></td>
      <td><?php echo $student_user->getProjJust5() ?></td>
      */ ?>
      <td><?php echo $student_user->getFormCompleted() ?></td>
      <td><?php echo $student_user->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="action"><a href="<?php echo url_for('student/new') ?>">Add New Student</a></div>
<div class="action"><a href="<?php echo "#" ?>">Import Students from File</a></div>