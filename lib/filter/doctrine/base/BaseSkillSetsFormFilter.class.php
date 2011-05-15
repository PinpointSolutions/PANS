<?php

/**
 * SkillSets filter form base class.
 *
 * @package    PANS
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSkillSetsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'area'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_visible' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'area'       => new sfValidatorPass(array('required' => false)),
      'is_visible' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('skill_sets_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SkillSets';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'area'       => 'Text',
      'is_visible' => 'Number',
    );
  }
}
