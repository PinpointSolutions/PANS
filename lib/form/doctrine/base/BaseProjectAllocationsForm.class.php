<?php

/**
 * ProjectAllocations form base class.
 *
 * @method ProjectAllocations getObject() Returns the current form's model object
 *
 * @package    PANS
 * @subpackage form
 * @author     Daniel Brose
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseProjectAllocationsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'project_id' => new sfWidgetFormInputHidden(),
      'snum1'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('StudentUsers'), 'add_empty' => true)),
      'snum2'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('StudentUsers_2'), 'add_empty' => true)),
      'snum3'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('StudentUsers_3'), 'add_empty' => true)),
      'snum4'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('StudentUsers_4'), 'add_empty' => true)),
      'snum5'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('StudentUsers_5'), 'add_empty' => true)),
      'snum6'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('StudentUsers_6'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'project_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('project_id')), 'empty_value' => $this->getObject()->get('project_id'), 'required' => false)),
      'snum1'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('StudentUsers'), 'required' => false)),
      'snum2'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('StudentUsers_2'), 'required' => false)),
      'snum3'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('StudentUsers_3'), 'required' => false)),
      'snum4'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('StudentUsers_4'), 'required' => false)),
      'snum5'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('StudentUsers_5'), 'required' => false)),
      'snum6'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('StudentUsers_6'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('project_allocations[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProjectAllocations';
  }

}
