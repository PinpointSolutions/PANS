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
	
	/* 
	For required fields we embed html tags that use css to display correct indicaters 
	
	 This is as the student nomination form is entirely generated. The only other way 
	 to affect the html itself than embedding would require a truly inordinate amount 
	 of coding, new templates and what have you.
	*/
	
	/* 
		INSTRUCTIONS LIST 
	This is an array containing the values used as the rollover instructions for the fields
	
	*/
	$instr = array( 
		//set the actual text used here
		'name' 			=> "This should also be the name on your student card., not nicknames etc",
		'pass_fail'		=> "Have you passed Project Management? If you have not, you can't enrol for 3001ICT" ,
		'degrees' 		=> ""  ,
		'majors' 		=> ""  ,
		'skills' 		=> ""  ,
		'gpa' 			=> "Cumulative Total, Not your last term total"  ,
		'projPref' 		=> "Select from the Dropdown Box"  ,
		'projJust'		=> "Why should you be selected for this project? You must provide one for your first preference."  ,
		'desiredStud' 	=> "Start typing the students first name"  ,
		'undesiredStud'	=> "Start typing the students first name"  ,
	);
	
	
    $first_name_widget = new sfWidgetFormInputText();
    $first_name_widget->setLabel('<span class="req">*</span>First Name 
	<a class="help" id="instructions_name" title="' . $instr['name'] . '">?</a>
	');
//	$first_name_widget->setAttribute('id', 'field_first_name');// adds the attribute to the widget itself, cannot make it affect the label. dont even try the formatted id methods, trust me. Stuck with embedding html to the label after 5 hours wasted effort :+
    $first_name_widget->setAttribute('title', $instr['name']); 
   
    $last_name_widget = new sfWidgetFormInputText();
    $last_name_widget->setLabel('<span class="req">*</span>Last Name ');
   
	
    $pass_fail_widget = new sfWidgetFormInputCheckbox();
    $pass_fail_widget->setLabel('<span class="req">*</span>Please check this box if you passed Project Management: <a class="help" id="instructions_pass_fail" title="' . $instr['pass_fail'] . '">?</a>');
	$pass_fail_widget->setAttribute('title', $instr['pass_fail']);
//	echo 'test = ' . ($pass_fail_widget->getIdFormat());
	
    // TODO: Fix saving majors
    // Also, Skill Sets
    // Possible solution:
    // http://www.mail-archive.com/symfony-users@googlegroups.com/msg11237.html
    $degrees_widget = new sfWidgetFormDoctrineChoice(
      array(
        'multiple' => true,
        'expanded' => true,
        'model' => $this->getRelatedModelName('Degree')));
    $degrees_widget->setLabel('<a id="instructions_degrees" title="' . $instr['degrees'] . '"><span class="req">*</span>Please indicate your Degree(s) </a>');	
	$degrees_widget->setAttribute('title', $instr['degrees']); 
	 
	 
	// MAJORS
    $majors_widget = new sfWidgetFormDoctrineChoice(
      array(
        'multiple' => true,
        'expanded' => true,
        'model' => $this->getRelatedModelName('Major')));
    $majors_widget->setLabel('<a  title="' . $instr['majors'] . '" id="instructions_majors"><span class="req">*</span>Please indicate your Major(s) </a>');	
	$majors_widget->setAttribute('title', $instr['majors']);

	// GPA
    $gpa_widget = new sfWidgetFormInputText();
    $gpa_widget->setLabel('<span class="req">*</span>Please indicate your current GPA <a id="instructions_gpa"class="help"  title="' . $instr['gpa'] . '">?</a>');	
	$gpa_widget->setAttribute('title', $instr['gpa']);
	
    // PROJECT PREF's
    $proj1_widget = new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true));
    $proj1_widget->setLabel('<span class="req" id="degrees">*</span>Nominate your first project preference <a id="instructions_projPref" class="help"  title="' . $instr['projPref'] . '">?</a>');	
	$proj1_widget->setAttribute('title', $instr['projPref']);

	
    $proj2_widget = new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true));
    $proj2_widget->setLabel('Nominate your second project preference');	
    $proj2_widget->setAttribute('title', $instr['projPref']);
	
    $proj3_widget = new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true));
    $proj3_widget->setLabel('Nominate your third project preference');	
    $proj3_widget->setAttribute('title', $instr['projPref']);
	
    $proj4_widget = new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true));
    $proj4_widget->setLabel('Nominate your fourth project preference');	
    $proj4_widget->setAttribute('title', $instr['projPref']);
	
    $proj5_widget = new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true));
    $proj5_widget->setLabel('Nominate your fifth project preference');	
    $proj5_widget->setAttribute('title', $instr['projPref']);
	
	
	// SKILLS
    $skills_widget = new sfWidgetFormDoctrineChoice(
      array(
        'multiple' => true,
        'expanded' => true,
        'model' => $this->getRelatedModelName('SkillSet')));
    $skills_widget->setLabel('<span class="req">*</span>What are your strengths? <a id="instructions_skills"></a>');	
	$skills_widget->setAttribute('title', $instr['skills']); 

	// STUDENT PREFFERANCES
	 // TODO: Make sure students can't pick themselves.
    // Possible solution is to do the checking in PHP upon save.
    $ystupref1_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $ystupref1_widget->setLabel('Please nominate up to five students you would like to work with. <a id="instructions_desiredStud" class="help"  title="' . $instr['desiredStud'] . '">?</a>');	
	$ystupref1_widget->setAttribute('title', $instr['desiredStud']); 
	
  
    $ystupref2_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $ystupref2_widget->setLabel(' ');
    $ystupref2_widget->setAttribute('title', $instr['desiredStud']); 
	
	
    $ystupref3_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $ystupref3_widget->setLabel(' ');	
    $ystupref3_widget->setAttribute('title', $instr['desiredStud']); 
	
	
    $ystupref4_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $ystupref4_widget->setLabel(' ');	
    $ystupref4_widget->setAttribute('title', $instr['desiredStud']); 
	
	
    $ystupref5_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $ystupref5_widget->setLabel(' ');	
    $ystupref5_widget->setAttribute('title', $instr['desiredStud']); 
	
	
	
	// UNDESIRED STUDENT PREF
    $nstupref1_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $nstupref1_widget->setLabel('Please nominate five students that you would NOT like to work with. <a id="instructions_undesiredStud" class="help"  title="' . $instr['undesiredStud'] . '">?</a>');  
	$nstupref1_widget->setAttribute('title', $instr['undesiredStud']); 
  


    $nstupref2_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $nstupref2_widget->setLabel(' ');
    $nstupref2_widget->setAttribute('title', $instr['undesiredStud']); 
	
    $nstupref3_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $nstupref3_widget->setLabel(' ');
    $nstupref3_widget->setAttribute('title', $instr['undesiredStud']); 
	
    $nstupref4_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $nstupref4_widget->setLabel(' ');
    $nstupref4_widget->setAttribute('title', $instr['undesiredStud']); 
	
    $nstupref5_widget = new sfWidgetFormChoice(array(
      'choices'           => array(),
      'renderer_class'    => 'sfWidgetFormDoctrineJQueryAutocompleter',
      'renderer_options'  => array('url'   => $this->getOption('url'),
                                   'model' => $this->getRelatedModelName('StudentUser'))
      )); 
    $nstupref5_widget->setLabel(' ');
    $nstupref5_widget->setAttribute('title', $instr['undesiredStud']); 
	
	
	// PROJECT JUSTIFICATIONS 
    $proj_just1_widget = new sfWidgetFormTextarea();
    $proj_just1_widget->setLabel('<span class="req">*</span>Please provide a justification.  <a id="instructions_projJust "class="help"  title="' . $instr['projJust'] . '">?</a>');
	$proj_just1_widget->setAttribute('title', $instr['projJust']); 
 

	
	$proj_just2_widget = new sfWidgetFormTextarea();
    $proj_just2_widget->setLabel('Please provide a justification.');
    $proj_just2_widget->setAttribute('title', $instr['projJust']);
	
	
    $proj_just3_widget = new sfWidgetFormTextarea();
    $proj_just3_widget->setLabel('Please provide a justification.');
    $proj_just3_widget->setAttribute('title', $instr['projJust']);
	
	
    $proj_just4_widget = new sfWidgetFormTextarea();
    $proj_just4_widget->setLabel('Please provide a justification.');
    $proj_just4_widget->setAttribute('title', $instr['projJust']);
	
	
    $proj_just5_widget = new sfWidgetFormTextarea();
    $proj_just5_widget->setLabel('Please provide a justification.');
    $proj_just5_widget->setAttribute('title', $instr['projJust']);
	
	
	
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
