<h1>Majors List</h1>

<table>
  <thead>
    <tr>
      <th>Snum</th>
      <th>Nomination round</th>
      <th>Pass fail pm</th>
      <th>Major ids</th>
      <th>Gpa</th>
      <th>Proj pref 1</th>
      <th>Proj pref 2</th>
      <th>Proj pref 3</th>
      <th>Proj pref 4</th>
      <th>Proj pref 5</th>
      <th>Skill set ids</th>
      <th>Y stu pref 1</th>
      <th>Y stu pref 2</th>
      <th>Y stu pref 3</th>
      <th>Y stu pref 4</th>
      <th>Y stu pref 5</th>
      <th>N stu pref 1</th>
      <th>N stu pref 2</th>
      <th>N stu pref 3</th>
      <th>N stu pref 4</th>
      <th>N stu pref 5</th>
      <th>Proj just 1</th>
      <th>Proj just 2</th>
      <th>Proj just 3</th>
      <th>Proj just 4</th>
      <th>Proj just 5</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($majors as $student_prefs): ?>
    <tr>
      <td><a href="<?php echo url_for('prefs/show?snum='.$student_prefs->getSnum().'&nomination_round='.$student_prefs->getNominationRound()) ?>"><?php echo $student_prefs->getSnum() ?></a></td>
      <td><a href="<?php echo url_for('prefs/show?snum='.$student_prefs->getSnum().'&nomination_round='.$student_prefs->getNominationRound()) ?>"><?php echo $student_prefs->getNominationRound() ?></a></td>
      <td><?php echo $student_prefs->getPassFailPm() ?></td>
      <td><?php echo $student_prefs->getMajorIds() ?></td>
      <td><?php echo $student_prefs->getGpa() ?></td>
      <td><?php echo $student_prefs->getProjPref1() ?></td>
      <td><?php echo $student_prefs->getProjPref2() ?></td>
      <td><?php echo $student_prefs->getProjPref3() ?></td>
      <td><?php echo $student_prefs->getProjPref4() ?></td>
      <td><?php echo $student_prefs->getProjPref5() ?></td>
      <td><?php echo $student_prefs->getSkillSetIds() ?></td>
      <td><?php echo $student_prefs->getYStuPref1() ?></td>
      <td><?php echo $student_prefs->getYStuPref2() ?></td>
      <td><?php echo $student_prefs->getYStuPref3() ?></td>
      <td><?php echo $student_prefs->getYStuPref4() ?></td>
      <td><?php echo $student_prefs->getYStuPref5() ?></td>
      <td><?php echo $student_prefs->getNStuPref1() ?></td>
      <td><?php echo $student_prefs->getNStuPref2() ?></td>
      <td><?php echo $student_prefs->getNStuPref3() ?></td>
      <td><?php echo $student_prefs->getNStuPref4() ?></td>
      <td><?php echo $student_prefs->getNStuPref5() ?></td>
      <td><?php echo $student_prefs->getProjJust1() ?></td>
      <td><?php echo $student_prefs->getProjJust2() ?></td>
      <td><?php echo $student_prefs->getProjJust3() ?></td>
      <td><?php echo $student_prefs->getProjJust4() ?></td>
      <td><?php echo $student_prefs->getProjJust5() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('prefs/new') ?>">New</a>
