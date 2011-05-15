<?php

/**
 * StudentUsers form base class.
 *
 * @method StudentUsers getObject() Returns the current form's model object
 *
 * @package    PANS
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseStudentUsersForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'snum'   => new sfWidgetFormInputHidden(),
      'p_word' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'snum'   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('snum')), 'empty_value' => $this->getObject()->get('snum'), 'required' => false)),
      'p_word' => new sfValidatorString(array('max_length' => 64)),
    ));

    $this->widgetSchema->setNameFormat('student_users[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StudentUsers';
  }

}
