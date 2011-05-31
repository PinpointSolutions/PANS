<table>
  <tbody>
    <tr>
      <th>Student Number:</th>
      <td><?php echo $student_user->getSnum() ?></td>
    </tr>
    <tr>
      <th>First Name:</th>
      <td><?php echo $student_user->getFirstName() ?></td>
    </tr>
    <tr>
      <th>Last Name:</th>
      <td><?php echo $student_user->getLastName() ?></td>
    </tr>
    <tr>
      <th>Passed Project Management:</th>
      <td><?php echo $student_user->getPassFailPm() ?></td>
    </tr>
    <tr>
      <th>Majors:</th>
      <td><?php echo $student_user->getMajorIds() ?></td>
    </tr>
    <tr>
      <th>GPA:</th>
      <td><?php echo $student_user->getGpa() ?></td>
    </tr>
    <tr>
      <th>First Project Preference:</th>
      <td><?php echo $student_user->getProjPref1() ?></td>
    </tr>
    <tr>
      <th>Justification:</th>
      <td><?php echo $student_user->getProjJust1() ?></td>
    </tr>
    <tr>
      <th>Second Project Preference:</th>
      <td><?php echo $student_user->getProjPref2() ?></td>
    </tr>
    <tr>
      <th>Justification:</th>
      <td><?php echo $student_user->getProjJust2() ?></td>
    </tr>
    <tr>
      <th>Third Project Preference:</th>
      <td><?php echo $student_user->getProjPref3() ?></td>
    </tr>
    <tr>
      <th>Justification:</th>
      <td><?php echo $student_user->getProjJust3() ?></td>
    </tr>
    <tr>
      <th>Fourth Project Preference:</th>
      <td><?php echo $student_user->getProjPref4() ?></td>
    </tr>
    <tr>
      <th>Justification:</th>
      <td><?php echo $student_user->getProjJust4() ?></td>
    </tr>
    <tr>
      <th>Fifth Project Preference:</th>
      <td><?php echo $student_user->getProjPref5() ?></td>
    </tr>
    <tr>
      <th>Justification:</th>
      <td><?php echo $student_user->getProjJust5() ?></td>
    </tr>
    <tr>
      <th>Skills:</th>
      <td><?php echo $student_user->getSkillSetIds() ?></td>
    </tr>
    <tr>
      <th>Students preferred to work with:</th>
      <td><?php echo $student_user->getYStuPref1() ?></td>
    </tr>
    <tr>
      <th></th>
      <td><?php echo $student_user->getYStuPref2() ?></td>
    </tr>
    <tr>
      <th></th>
      <td><?php echo $student_user->getYStuPref3() ?></td>
    </tr>
    <tr>
      <th></th>
      <td><?php echo $student_user->getYStuPref4() ?></td>
    </tr>
    <tr>
      <th></th>
      <td><?php echo $student_user->getYStuPref5() ?></td>
    </tr>
    <tr>
      <th>Students undesired to work with:</th>
      <td><?php echo $student_user->getNStuPref1() ?></td>
    </tr>
    <tr>
      <th></th>
      <td><?php echo $student_user->getNStuPref2() ?></td>
    </tr>
    <tr>
      <th></th>
      <td><?php echo $student_user->getNStuPref3() ?></td>
    </tr>
    <tr>
      <th></th>
      <td><?php echo $student_user->getNStuPref4() ?></td>
    </tr>
    <tr>
      <th></th>
      <td><?php echo $student_user->getNStuPref5() ?></td>
    </tr>
    <tr>
      <th>Form completed:</th>
      <td><?php echo $student_user->getFormCompleted() ?></td>
    </tr>
    <tr>
      <th>Last modified:</th>
      <td><?php echo $student_user->getModifiedTime() ?></td>
    </tr>
  </tbody>
</table>

<div class="action"><a href="<?php echo url_for('student/edit?snum='.$student_user->getSnum()) ?>">Edit This Student</a></div>

<div class="action"><a href="<?php echo url_for('student/index') ?>">Back to Student List</a></div>
