<?php

/**
 * Majors form base class.
 *
 * @method Majors getObject() Returns the current form's model object
 *
 * @package    PANS
 * @subpackage form
 * @author     Daniel Brose
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseMajorsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'major'      => new sfWidgetFormInputText(),
      'is_visible' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'major'      => new sfValidatorString(array('max_length' => 32)),
      'is_visible' => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('majors[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Majors';
  }

}
