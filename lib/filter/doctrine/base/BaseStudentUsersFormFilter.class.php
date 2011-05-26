<?php

/**
 * StudentUsers filter form base class.
 *
 * @package    PANS
 * @subpackage filter
 * @author     Daniel Brose
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseStudentUsersFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'p_word'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'first_name' => new sfWidgetFormFilterInput(),
      'last_name'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'p_word'     => new sfValidatorPass(array('required' => false)),
      'first_name' => new sfValidatorPass(array('required' => false)),
      'last_name'  => new sfValidatorPass(array('required' => false)),
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
      'snum'       => 'Number',
      'p_word'     => 'Text',
      'first_name' => 'Text',
      'last_name'  => 'Text',
    );
  }
}
