<?php

/**
 * Projects form base class.
 *
 * @method Projects getObject() Returns the current form's model object
 *
 * @package    PANS
 * @subpackage form
 * @author     Daniel Brose
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseProjectsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'title'               => new sfWidgetFormInputText(),
      'organisation'        => new sfWidgetFormInputText(),
      'description'         => new sfWidgetFormTextarea(),
      'has_additional_info' => new sfWidgetFormInputCheckbox(),
      'major_ids'           => new sfWidgetFormInputText(),
      'skill_set_ids'       => new sfWidgetFormInputText(),
      'nomination_round'    => new sfWidgetFormInputText(),
      'proj_num'            => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'               => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'organisation'        => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'description'         => new sfValidatorString(array('required' => false)),
      'has_additional_info' => new sfValidatorBoolean(array('required' => false)),
      'major_ids'           => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'skill_set_ids'       => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'nomination_round'    => new sfValidatorString(array('max_length' => 64)),
      'proj_num'            => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('projects[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Projects';
  }

}
