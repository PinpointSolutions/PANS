<table>
  <tbody>
    <tr>
      <th>Snum:</th>
      <td><?php echo $student_prefs->getSnum() ?></td>
    </tr>
    <tr>
      <th>Nomination round:</th>
      <td><?php echo $student_prefs->getNominationRound() ?></td>
    </tr>
    <tr>
      <th>Pass fail pm:</th>
      <td><?php echo $student_prefs->getPassFailPm() ?></td>
    </tr>
    <tr>
      <th>Major ids:</th>
      <td><?php echo $student_prefs->getMajorIds() ?></td>
    </tr>
    <tr>
      <th>Gpa:</th>
      <td><?php echo $student_prefs->getGpa() ?></td>
    </tr>
    <tr>
      <th>Proj pref 1:</th>
      <td><?php echo $student_prefs->getProjPref1() ?></td>
    </tr>
    <tr>
      <th>Proj pref 2:</th>
      <td><?php echo $student_prefs->getProjPref2() ?></td>
    </tr>
    <tr>
      <th>Proj pref 3:</th>
      <td><?php echo $student_prefs->getProjPref3() ?></td>
    </tr>
    <tr>
      <th>Proj pref 4:</th>
      <td><?php echo $student_prefs->getProjPref4() ?></td>
    </tr>
    <tr>
      <th>Proj pref 5:</th>
      <td><?php echo $student_prefs->getProjPref5() ?></td>
    </tr>
    <tr>
      <th>Skill set ids:</th>
      <td><?php echo $student_prefs->getSkillSetIds() ?></td>
    </tr>
    <tr>
      <th>Y stu pref 1:</th>
      <td><?php echo $student_prefs->getYStuPref1() ?></td>
    </tr>
    <tr>
      <th>Y stu pref 2:</th>
      <td><?php echo $student_prefs->getYStuPref2() ?></td>
    </tr>
    <tr>
      <th>Y stu pref 3:</th>
      <td><?php echo $student_prefs->getYStuPref3() ?></td>
    </tr>
    <tr>
      <th>Y stu pref 4:</th>
      <td><?php echo $student_prefs->getYStuPref4() ?></td>
    </tr>
    <tr>
      <th>Y stu pref 5:</th>
      <td><?php echo $student_prefs->getYStuPref5() ?></td>
    </tr>
    <tr>
      <th>N stu pref 1:</th>
      <td><?php echo $student_prefs->getNStuPref1() ?></td>
    </tr>
    <tr>
      <th>N stu pref 2:</th>
      <td><?php echo $student_prefs->getNStuPref2() ?></td>
    </tr>
    <tr>
      <th>N stu pref 3:</th>
      <td><?php echo $student_prefs->getNStuPref3() ?></td>
    </tr>
    <tr>
      <th>N stu pref 4:</th>
      <td><?php echo $student_prefs->getNStuPref4() ?></td>
    </tr>
    <tr>
      <th>N stu pref 5:</th>
      <td><?php echo $student_prefs->getNStuPref5() ?></td>
    </tr>
    <tr>
      <th>Proj just 1:</th>
      <td><?php echo $student_prefs->getProjJust1() ?></td>
    </tr>
    <tr>
      <th>Proj just 2:</th>
      <td><?php echo $student_prefs->getProjJust2() ?></td>
    </tr>
    <tr>
      <th>Proj just 3:</th>
      <td><?php echo $student_prefs->getProjJust3() ?></td>
    </tr>
    <tr>
      <th>Proj just 4:</th>
      <td><?php echo $student_prefs->getProjJust4() ?></td>
    </tr>
    <tr>
      <th>Proj just 5:</th>
      <td><?php echo $student_prefs->getProjJust5() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('prefs/edit?snum='.$student_prefs->getSnum().'&nomination_round='.$student_prefs->getNominationRound()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('prefs/index') ?>">List</a>
