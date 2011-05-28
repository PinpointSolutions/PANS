<table>
  <tbody>
    <tr>
      <th>Snum:</th>
      <td><?php echo $student_user->getSnum() ?></td>
    </tr>
    <tr>
      <th>P word:</th>
      <td><?php echo $student_user->getPWord() ?></td>
    </tr>
    <tr>
      <th>First name:</th>
      <td><?php echo $student_user->getFirstName() ?></td>
    </tr>
    <tr>
      <th>Last name:</th>
      <td><?php echo $student_user->getLastName() ?></td>
    </tr>
    <tr>
      <th>Pass fail pm:</th>
      <td><?php echo $student_user->getPassFailPm() ?></td>
    </tr>
    <tr>
      <th>Major ids:</th>
      <td><?php echo $student_user->getMajorIds() ?></td>
    </tr>
    <tr>
      <th>Gpa:</th>
      <td><?php echo $student_user->getGpa() ?></td>
    </tr>
    <tr>
      <th>Proj pref1:</th>
      <td><?php echo $student_user->getProjPref1() ?></td>
    </tr>
    <tr>
      <th>Proj pref2:</th>
      <td><?php echo $student_user->getProjPref2() ?></td>
    </tr>
    <tr>
      <th>Proj pref3:</th>
      <td><?php echo $student_user->getProjPref3() ?></td>
    </tr>
    <tr>
      <th>Proj pref4:</th>
      <td><?php echo $student_user->getProjPref4() ?></td>
    </tr>
    <tr>
      <th>Proj pref5:</th>
      <td><?php echo $student_user->getProjPref5() ?></td>
    </tr>
    <tr>
      <th>Skill set ids:</th>
      <td><?php echo $student_user->getSkillSetIds() ?></td>
    </tr>
    <tr>
      <th>Y stu pref1:</th>
      <td><?php echo $student_user->getYStuPref1() ?></td>
    </tr>
    <tr>
      <th>Y stu pref2:</th>
      <td><?php echo $student_user->getYStuPref2() ?></td>
    </tr>
    <tr>
      <th>Y stu pref3:</th>
      <td><?php echo $student_user->getYStuPref3() ?></td>
    </tr>
    <tr>
      <th>Y stu pref4:</th>
      <td><?php echo $student_user->getYStuPref4() ?></td>
    </tr>
    <tr>
      <th>Y stu pref5:</th>
      <td><?php echo $student_user->getYStuPref5() ?></td>
    </tr>
    <tr>
      <th>N stu pref1:</th>
      <td><?php echo $student_user->getNStuPref1() ?></td>
    </tr>
    <tr>
      <th>N stu pref2:</th>
      <td><?php echo $student_user->getNStuPref2() ?></td>
    </tr>
    <tr>
      <th>N stu pref3:</th>
      <td><?php echo $student_user->getNStuPref3() ?></td>
    </tr>
    <tr>
      <th>N stu pref4:</th>
      <td><?php echo $student_user->getNStuPref4() ?></td>
    </tr>
    <tr>
      <th>N stu pref5:</th>
      <td><?php echo $student_user->getNStuPref5() ?></td>
    </tr>
    <tr>
      <th>Proj just1:</th>
      <td><?php echo $student_user->getProjJust1() ?></td>
    </tr>
    <tr>
      <th>Proj just2:</th>
      <td><?php echo $student_user->getProjJust2() ?></td>
    </tr>
    <tr>
      <th>Proj just3:</th>
      <td><?php echo $student_user->getProjJust3() ?></td>
    </tr>
    <tr>
      <th>Proj just4:</th>
      <td><?php echo $student_user->getProjJust4() ?></td>
    </tr>
    <tr>
      <th>Proj just5:</th>
      <td><?php echo $student_user->getProjJust5() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('student/edit?snum='.$student_user->getSnum()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('student/index') ?>">List</a>
