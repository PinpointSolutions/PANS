<?php

/**
 * StudentUsers filter form base class.
 *
 * @package    PANS
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseStudentUsersFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'p_word' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'p_word' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('student_users_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StudentUsers';
  }

  public function getFields()
  {
    return array(
      'snum'   => 'Number',
      'p_word' => 'Text',
    );
  }
}
