<?php

/**
 * Projects filter form base class.
 *
 * @package    PANS
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseProjectsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'               => new sfWidgetFormFilterInput(),
      'organisation'        => new sfWidgetFormFilterInput(),
      'description'         => new sfWidgetFormFilterInput(),
      'has_additional_info' => new sfWidgetFormFilterInput(),
      'major_ids'           => new sfWidgetFormFilterInput(),
      'skill_set_ids'       => new sfWidgetFormFilterInput(),
      'nomination_round'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'proj_num'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'title'               => new sfValidatorPass(array('required' => false)),
      'organisation'        => new sfValidatorPass(array('required' => false)),
      'description'         => new sfValidatorPass(array('required' => false)),
      'has_additional_info' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'major_ids'           => new sfValidatorPass(array('required' => false)),
      'skill_set_ids'       => new sfValidatorPass(array('required' => false)),
      'nomination_round'    => new sfValidatorPass(array('required' => false)),
      'proj_num'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('projects_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Projects';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'title'               => 'Text',
      'organisation'        => 'Text',
      'description'         => 'Text',
      'has_additional_info' => 'Number',
      'major_ids'           => 'Text',
      'skill_set_ids'       => 'Text',
      'nomination_round'    => 'Text',
      'proj_num'            => 'Number',
    );
  }
}
