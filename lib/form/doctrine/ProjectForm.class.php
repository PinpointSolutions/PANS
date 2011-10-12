<?php

/**
 * Project form.
 *
 * @package    PANS
 * @subpackage form
 * @author     Pinpoint Solutions
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProjectForm extends BaseProjectForm
{
  public function setup()
  {
    //call the parent configure
    //parent::configure();

    $instructions_widget = new sfWidgetFormSchema(array(),array(),array('class'=>'instruction_schema'),array(''));
    $instructions_widget -> setLabel('Degrees, Majors, Skills <span class="smaller">For each of these use the appropriate ID\'s seperated by a single space</span>'); 
     
    
    //we have to define each widget if we call setWidgets, even those not being customised
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      //see http://www.symfony-project.org/api/1_4/sfWidgetForm#method___construct
      'title'               =>  new sfWidgetFormInputText(),//$title_widget
      'organisation'        => new sfWidgetFormTextarea(),
      'description'         => new sfWidgetFormTextarea(),
      'extended_description' => new sfWidgetFormTextarea(),
      'has_additional_info' => new sfWidgetFormInputCheckbox(),
      'has_gpa_cutoff'      => new sfWidgetFormInputCheckbox(array(),array('title'=>'This assumes a 5.0 cutoff')),
      'max_group_size'       => new sfWidgetFormInputText(),
      'instructions'        => $instructions_widget,
      'degree_ids'          => new sfWidgetFormInputText(),
      'major_ids'           => new sfWidgetFormInputText(),
      'skill_set_ids'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'                => new sfValidatorString(array('max_length' => 120)),
      'organisation'         => new sfValidatorString(array('max_length' => 120, 'required' => false)),
      'description'          => new sfValidatorString(array('required' => false)),
      'extended_description' => new sfValidatorString(array('required' => false)),
      'has_additional_info'  => new sfValidatorBoolean(array('required' => false)),
      'has_gpa_cutoff'       => new sfValidatorBoolean(array('required' => false)),
      'max_group_size'       => new sfValidatorInteger(array('required' => false)),
      'degree_ids'           => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'major_ids'            => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'skill_set_ids'        => new sfValidatorString(array('max_length' => 64, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('project[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();
  }
}
