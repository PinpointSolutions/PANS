<?php

/**
 * ProjectAllocation form.
 *
 * @package    PANS
 * @subpackage form
 * @author     Pinpoint Solutions
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProjectAllocationForm extends BaseProjectAllocationForm
{
  public function configure()
  {
  
  //in order to make the snum a text input rather than a dropdown of names we have to redeclare both set and validators
    parent::configure();
  
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'project_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true)),
      'snum'       => new sfWidgetFormInputText(),
    ));
    
    
    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'project_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'required' => false)),
      'snum'       => new sfValidatorNumber(),
    ));
    
    
    $this->widgetSchema->setNameFormat('project_allocation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

 
    
    
  }
}
