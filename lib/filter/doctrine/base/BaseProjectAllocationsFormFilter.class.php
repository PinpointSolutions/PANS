<?php

/**
 * ProjectAllocations filter form base class.
 *
 * @package    PANS
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseProjectAllocationsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'snum1'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('StudentUsers'), 'add_empty' => true)),
      'snum2'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('StudentUsers_2'), 'add_empty' => true)),
      'snum3'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('StudentUsers_3'), 'add_empty' => true)),
      'snum4'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('StudentUsers_4'), 'add_empty' => true)),
      'snum5'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('StudentUsers_5'), 'add_empty' => true)),
      'snum6'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('StudentUsers_6'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'snum1'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('StudentUsers'), 'column' => 'snum')),
      'snum2'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('StudentUsers_2'), 'column' => 'snum')),
      'snum3'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('StudentUsers_3'), 'column' => 'snum')),
      'snum4'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('StudentUsers_4'), 'column' => 'snum')),
      'snum5'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('StudentUsers_5'), 'column' => 'snum')),
      'snum6'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('StudentUsers_6'), 'column' => 'snum')),
    ));

    $this->widgetSchema->setNameFormat('project_allocations_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProjectAllocations';
  }

  public function getFields()
  {
    return array(
      'project_id' => 'Number',
      'snum1'      => 'ForeignKey',
      'snum2'      => 'ForeignKey',
      'snum3'      => 'ForeignKey',
      'snum4'      => 'ForeignKey',
      'snum5'      => 'ForeignKey',
      'snum6'      => 'ForeignKey',
    );
  }
}
