<?php

/**
 * Majors filter form base class.
 *
 * @package    PANS
 * @subpackage filter
 * @author     Daniel Brose
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseMajorsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'major'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_visible' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'major'      => new sfValidatorPass(array('required' => false)),
      'is_visible' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('majors_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Majors';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'major'      => 'Text',
      'is_visible' => 'Number',
    );
  }
}
