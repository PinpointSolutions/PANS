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
    // Prevent user from updating the timestamp fields
    unset(
      $this['created_at'],
      $this['updated_at'],
      $this['form_completed'],
      $this['snum']
    );
  
    /* $snum_widget = new sfWidgetFormInputHidden();
    $snum_widget->setLabel('Student Number'); */
	
    $first_name_widget = new sfWidgetFormInputText();
    $first_name_widget->setLabel('First Name');
    
    $last_name_widget = new sfWidgetFormInputText();
    $last_name_widget->setLabel('Last Name');
    
    $pass_fail_widget = new sfWidgetFormInputCheckbox();
    $pass_fail_widget->setLabel('Please check this box if you passed Project Management: ');
    
    // TODO: Fix saving majors
    // Also, Skill Sets
    // Possible solution:
    // http://www.mail-archive.com/symfony-users@googlegroups.com/msg11237.html
    $degrees_widget = new sfWidgetFormDoctrineChoice(
      array(
        'multiple' => true,
        'expanded' => true,
        'model' => $this->getRelatedModelName('Degree')));
    $degrees_widget->setLabel('Please indicate your Degree(s)');	
    
    $majors_widget = new sfWidgetFormDoctrineChoice(
      array(
        'multiple' => true,
        'expanded' => true,
        'model' => $this->getRelatedModelName('Major')));
    $majors_widget->setLabel('Please indicate your Major(s), if applicable');	
    
    $gpa_widget = new sfWidgetFormInputText();
    $gpa_widget->setLabel('Please indicate your current GPA');	
    
    $proj1_widget = new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true));
    $proj1_widget->setLabel('Nominate your first project preference');	
    
    $proj2_widget = new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true));
    $proj2_widget->setLabel('Nominate your second project preference');	
    
    $proj3_widget = new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true));
    $proj3_widget->setLabel('Nominate your third project preference');	
    
    $proj4_widget = new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true));
    $proj4_widget->setLabel('Nominate your fourth project preference');	
    
    $proj5_widget = new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true));
    $proj5_widget->setLabel('Nominate your fifth project preference');	
    
    $skills_widget = new sfWidgetFormDoctrineChoice(
      array(
        'multiple' => true,
        'expanded' => true,
        'model' => $this->getRelatedModelName('SkillSet')));
    $skills_widget->setLabel('What are your strengths?');	
    
    // TODO: Make sure students can't pick themselves.
    // Possible solution is to do the checking in PHP upon save.
    $ystupref1_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $ystupref1_widget->setLabel('Please nominate up to five students you would like to work with.');	
    
    $ystupref2_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $ystupref2_widget->setLabel(' ');
    
    $ystupref3_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $ystupref3_widget->setLabel(' ');	
    
    $ystupref4_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $ystupref4_widget->setLabel(' ');	
    
    $ystupref5_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $ystupref5_widget->setLabel(' ');	
    
    $nstupref1_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $nstupref1_widget->setLabel('Please nominate five students that you would NOT like to work with.');	
    
    $nstupref2_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $nstupref2_widget->setLabel(' ');
    
    $nstupref3_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $nstupref3_widget->setLabel(' ');
    
    $nstupref4_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $nstupref4_widget->setLabel(' ');
    
    $nstupref5_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $nstupref5_widget->setLabel(' ');
    
    $proj_just1_widget = new sfWidgetFormTextarea();
    $proj_just1_widget->setLabel('Please provide a justification.<br> Why should you be selected for this project?<br>You must provide one for your first preference.');

    $proj_just2_widget = new sfWidgetFormTextarea();
    $proj_just2_widget->setLabel('Please provide a justification.');
    
    $proj_just3_widget = new sfWidgetFormTextarea();
    $proj_just3_widget->setLabel('Please provide a justification.');
    
    $proj_just4_widget = new sfWidgetFormTextarea();
    $proj_just4_widget->setLabel('Please provide a justification.');
    
    $proj_just5_widget = new sfWidgetFormTextarea();
    $proj_just5_widget->setLabel('Please provide a justification.');
    
    $this->setWidgets(array(
      /* 'snum' => $snum_widget, */
      'first_name' => $first_name_widget,
      'last_name' => $last_name_widget,
      'pass_fail_pm' => $pass_fail_widget,
      'degree_ids' => $degrees_widget,
      'major_ids' => $majors_widget,
      'skill_set_ids' => $skills_widget,
      'gpa' => $gpa_widget,
      'proj_pref1' => $proj1_widget,
      'proj_just1' => $proj_just1_widget,
      'proj_pref2' => $proj2_widget,
      'proj_just2' => $proj_just2_widget,
      'proj_pref3' => $proj3_widget,
      'proj_just3' => $proj_just3_widget,
      'proj_pref4' => $proj4_widget,
      'proj_just4' => $proj_just4_widget,
      'proj_pref5' => $proj5_widget,
      'proj_just5' => $proj_just5_widget,
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
    ));
    
    $this->widgetSchema->setNameFormat('student_user[%s]');
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    $this->setupInheritance();
  }  
}
