<?php

/**
 * StudentPrefs form base class.
 *
 * @method StudentPrefs getObject() Returns the current form's model object
 *
 * @package    PANS
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseStudentPrefsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'snum'          => new sfWidgetFormInputHidden(),
      'year'          => new sfWidgetFormInputText(),
      'pass_fail_pm'  => new sfWidgetFormInputText(),
      'major_ids'     => new sfWidgetFormInputText(),
      'gpa'           => new sfWidgetFormInputText(),
      'proj_pref_1'   => new sfWidgetFormInputText(),
      'proj_pref_2'   => new sfWidgetFormInputText(),
      'proj_pref_3'   => new sfWidgetFormInputText(),
      'proj_pref_4'   => new sfWidgetFormInputText(),
      'proj_pref_5'   => new sfWidgetFormInputText(),
      'skill_set_ids' => new sfWidgetFormInputText(),
      'y_stu_pref_1'  => new sfWidgetFormInputText(),
      'y_stu_pref_2'  => new sfWidgetFormInputText(),
      'y_stu_pref_3'  => new sfWidgetFormInputText(),
      'y_stu_pref_4'  => new sfWidgetFormInputText(),
      'y_stu_pref_5'  => new sfWidgetFormInputText(),
      'n_stu_pref_1'  => new sfWidgetFormInputText(),
      'n_stu_pref_2'  => new sfWidgetFormInputText(),
      'n_stu_pref_3'  => new sfWidgetFormInputText(),
      'n_stu_pref_4'  => new sfWidgetFormInputText(),
      'n_stu_pref_5'  => new sfWidgetFormInputText(),
      'proj_just_1'   => new sfWidgetFormTextarea(),
      'proj_just_2'   => new sfWidgetFormTextarea(),
      'proj_just_3'   => new sfWidgetFormTextarea(),
      'proj_just_4'   => new sfWidgetFormTextarea(),
      'proj_just_5'   => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'snum'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('snum')), 'empty_value' => $this->getObject()->get('snum'), 'required' => false)),
      'year'          => new sfValidatorInteger(),
      'pass_fail_pm'  => new sfValidatorInteger(array('required' => false)),
      'major_ids'     => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'gpa'           => new sfValidatorNumber(array('required' => false)),
      'proj_pref_1'   => new sfValidatorInteger(array('required' => false)),
      'proj_pref_2'   => new sfValidatorInteger(array('required' => false)),
      'proj_pref_3'   => new sfValidatorInteger(array('required' => false)),
      'proj_pref_4'   => new sfValidatorInteger(array('required' => false)),
      'proj_pref_5'   => new sfValidatorInteger(array('required' => false)),
      'skill_set_ids' => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'y_stu_pref_1'  => new sfValidatorInteger(array('required' => false)),
      'y_stu_pref_2'  => new sfValidatorInteger(array('required' => false)),
      'y_stu_pref_3'  => new sfValidatorInteger(array('required' => false)),
      'y_stu_pref_4'  => new sfValidatorInteger(array('required' => false)),
      'y_stu_pref_5'  => new sfValidatorInteger(array('required' => false)),
      'n_stu_pref_1'  => new sfValidatorInteger(array('required' => false)),
      'n_stu_pref_2'  => new sfValidatorInteger(array('required' => false)),
      'n_stu_pref_3'  => new sfValidatorInteger(array('required' => false)),
      'n_stu_pref_4'  => new sfValidatorInteger(array('required' => false)),
      'n_stu_pref_5'  => new sfValidatorInteger(array('required' => false)),
      'proj_just_1'   => new sfValidatorString(array('max_length' => 2048, 'required' => false)),
      'proj_just_2'   => new sfValidatorString(array('max_length' => 2048, 'required' => false)),
      'proj_just_3'   => new sfValidatorString(array('max_length' => 2048, 'required' => false)),
      'proj_just_4'   => new sfValidatorString(array('max_length' => 2048, 'required' => false)),
      'proj_just_5'   => new sfValidatorString(array('max_length' => 2048, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('student_prefs[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StudentPrefs';
  }

}
