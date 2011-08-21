<?php

/**
 * StudentUser form.
 *
 * @package    PANS
 * @subpackage form
 * @author     Pinpoint Solutions
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class StudentUserForm extends BaseStudentUserForm
{
  public function configure()
  {	
    unset(
      $this['created_at'],
      $this['updated_at'],
      $this['form_completed']
    );
  
    $snum_widget = new sfWidgetFormInputHidden();
    $snum_widget->setLabel('Student Number');
    
    $first_name_widget = new sfWidgetFormInputText();
    $first_name_widget->setLabel('First Name');
    
    $last_name_widget = new sfWidgetFormInputText();
    $last_name_widget->setLabel('Last Name');
    
    $pass_fail_widget = new sfWidgetFormInputCheckbox();
    $pass_fail_widget->setLabel('Please check this box if you passed Project Management: ');
    
    $majors_widget = new sfWidgetFormInputText();
    $majors_widget->setLabel('Indicate your Major/s');	
    
    $gpa_widget = new sfWidgetFormInputText();
    $gpa_widget->setLabel('Indicate your current GPA');	
    
    $proj1_widget = new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true));
    $proj1_widget->setLabel('Input your first project preference');	
    
    $proj2_widget = new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true));
    $proj2_widget->setLabel('Input your second project preference');	
    
    $proj3_widget = new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true));
    $proj3_widget->setLabel('Input your third project preference');	
    
    $proj4_widget = new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true));
    $proj4_widget->setLabel('Input your fourth project preference');	
    
    $proj5_widget = new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true));
    $proj5_widget->setLabel('Input your fifth project preference');	
    
    $skills_widget = new sfWidgetFormInputText();
    $skills_widget->setLabel('Indicate your skill set');	
    
    $ystupref1_widget = new sfWidgetFormInputText();
    $ystupref1_widget->setLabel('Indicate the first student you would like to work with');	
    
    $ystupref2_widget = new sfWidgetFormInputText();
    $ystupref2_widget->setLabel('Indicate the second student you would like to work with');	
    
    $ystupref3_widget = new sfWidgetFormInputText();
    $ystupref3_widget->setLabel('Indicate the third student you would like to work with');	
    
    $ystupref4_widget = new sfWidgetFormInputText();
    $ystupref4_widget->setLabel('Indicate the fourth student you would like to work with');	
    
    $ystupref5_widget = new sfWidgetFormInputText();
    $ystupref5_widget->setLabel('Indicate the fifth student you would like to work with');	
    
    $nstupref1_widget = new sfWidgetFormInputText();
    $nstupref1_widget->setLabel('Indicate the first student you would NOT like to work with');	
    
    $nstupref2_widget = new sfWidgetFormInputText();
    $nstupref2_widget->setLabel('Indicate the second student you would NOT like to work with');
    
    $nstupref3_widget = new sfWidgetFormInputText();
    $nstupref3_widget->setLabel('Indicate the third student you would NOT like to work with');
    
    $nstupref4_widget = new sfWidgetFormInputText();
    $nstupref4_widget->setLabel('Indicate the fourth student you would NOT like to work with');
    
    $nstupref5_widget = new sfWidgetFormInputText();
    $nstupref5_widget->setLabel('Indicate the fifth student you would NOT like to work with');
    
    $proj_just1_widget = new sfWidgetFormTextarea();
    $proj_just1_widget->setLabel('Provide a justification for your first project preference');

    $proj_just2_widget = new sfWidgetFormTextarea();
    $proj_just2_widget->setLabel('Provide a justification for your second project preference');
    
    $proj_just3_widget = new sfWidgetFormTextarea();
    $proj_just3_widget->setLabel('Provide a justification for your third project preference');
    
    $proj_just4_widget = new sfWidgetFormTextarea();
    $proj_just4_widget->setLabel('Provide a justification for your fourth project preference');
    
    $proj_just5_widget = new sfWidgetFormTextarea();
    $proj_just5_widget->setLabel('Provide a justification for your fifth project preference');
    
    $this->setWidgets(array(
      'snum' => $snum_widget,
      'first_name' => $first_name_widget,
      'last_name' => $last_name_widget,
      'pass_fail_pm' => $pass_fail_widget,
      'major_ids' => $majors_widget,
      'gpa' => $gpa_widget,
      'proj_pref1' => $proj1_widget,
      'proj_pref2' => $proj2_widget,
      'proj_pref3' => $proj3_widget,
      'proj_pref4' => $proj4_widget,
      'proj_pref5' => $proj5_widget,
      'skill_set_ids' => $skills_widget,
      'y_stu_pref1' => $ystupref1_widget,
      'y_stu_pref2' => $ystupref2_widget,
      'y_stu_pref3' => $ystupref3_widget,
      'y_stu_pref4' => $ystupref4_widget,
      'y_stu_pref5' => $ystupref5_widget,
      'n_stu_pref1' => $nstupref1_widget,
      'n_stu_pref2' => $nstupref2_widget,
      'n_stu_pref3' => $nstupref3_widget,
      'n_stu_pref4' => $nstupref4_widget,
      'n_stu_pref5' => $nstupref5_widget,
      'proj_just1' => $proj_just1_widget,
      'proj_just2' => $proj_just2_widget,
      'proj_just3' => $proj_just3_widget,
      'proj_just4' => $proj_just4_widget,
      'proj_just5' => $proj_just5_widget,
    ));
    
    
    $this->widgetSchema->setNameFormat('student_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

  }  
}
