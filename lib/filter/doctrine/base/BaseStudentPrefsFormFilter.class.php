<?php

/**
 * StudentPrefs filter form base class.
 *
 * @package    PANS
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseStudentPrefsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'year'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'pass_fail_pm'  => new sfWidgetFormFilterInput(),
      'major_ids'     => new sfWidgetFormFilterInput(),
      'gpa'           => new sfWidgetFormFilterInput(),
      'proj_pref_1'   => new sfWidgetFormFilterInput(),
      'proj_pref_2'   => new sfWidgetFormFilterInput(),
      'proj_pref_3'   => new sfWidgetFormFilterInput(),
      'proj_pref_4'   => new sfWidgetFormFilterInput(),
      'proj_pref_5'   => new sfWidgetFormFilterInput(),
      'skill_set_ids' => new sfWidgetFormFilterInput(),
      'y_stu_pref_1'  => new sfWidgetFormFilterInput(),
      'y_stu_pref_2'  => new sfWidgetFormFilterInput(),
      'y_stu_pref_3'  => new sfWidgetFormFilterInput(),
      'y_stu_pref_4'  => new sfWidgetFormFilterInput(),
      'y_stu_pref_5'  => new sfWidgetFormFilterInput(),
      'n_stu_pref_1'  => new sfWidgetFormFilterInput(),
      'n_stu_pref_2'  => new sfWidgetFormFilterInput(),
      'n_stu_pref_3'  => new sfWidgetFormFilterInput(),
      'n_stu_pref_4'  => new sfWidgetFormFilterInput(),
      'n_stu_pref_5'  => new sfWidgetFormFilterInput(),
      'proj_just_1'   => new sfWidgetFormFilterInput(),
      'proj_just_2'   => new sfWidgetFormFilterInput(),
      'proj_just_3'   => new sfWidgetFormFilterInput(),
      'proj_just_4'   => new sfWidgetFormFilterInput(),
      'proj_just_5'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'year'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pass_fail_pm'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'major_ids'     => new sfValidatorPass(array('required' => false)),
      'gpa'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'proj_pref_1'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'proj_pref_2'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'proj_pref_3'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'proj_pref_4'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'proj_pref_5'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'skill_set_ids' => new sfValidatorPass(array('required' => false)),
      'y_stu_pref_1'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'y_stu_pref_2'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'y_stu_pref_3'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'y_stu_pref_4'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'y_stu_pref_5'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'n_stu_pref_1'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'n_stu_pref_2'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'n_stu_pref_3'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'n_stu_pref_4'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'n_stu_pref_5'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'proj_just_1'   => new sfValidatorPass(array('required' => false)),
      'proj_just_2'   => new sfValidatorPass(array('required' => false)),
      'proj_just_3'   => new sfValidatorPass(array('required' => false)),
      'proj_just_4'   => new sfValidatorPass(array('required' => false)),
      'proj_just_5'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('student_prefs_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StudentPrefs';
  }

  public function getFields()
  {
    return array(
      'snum'          => 'Number',
      'year'          => 'Number',
      'pass_fail_pm'  => 'Number',
      'major_ids'     => 'Text',
      'gpa'           => 'Number',
      'proj_pref_1'   => 'Number',
      'proj_pref_2'   => 'Number',
      'proj_pref_3'   => 'Number',
      'proj_pref_4'   => 'Number',
      'proj_pref_5'   => 'Number',
      'skill_set_ids' => 'Text',
      'y_stu_pref_1'  => 'Number',
      'y_stu_pref_2'  => 'Number',
      'y_stu_pref_3'  => 'Number',
      'y_stu_pref_4'  => 'Number',
      'y_stu_pref_5'  => 'Number',
      'n_stu_pref_1'  => 'Number',
      'n_stu_pref_2'  => 'Number',
      'n_stu_pref_3'  => 'Number',
      'n_stu_pref_4'  => 'Number',
      'n_stu_pref_5'  => 'Number',
      'proj_just_1'   => 'Text',
      'proj_just_2'   => 'Text',
      'proj_just_3'   => 'Text',
      'proj_just_4'   => 'Text',
      'proj_just_5'   => 'Text',
    );
  }
}
