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
  public function configure()
  {
    //call the parent configure
    parent::configure();

    
/*
    $title_widget = new sfWidgetFormInputText();
    $schema_widget = new sfWidgetFormSchema(array($title_widget));
    $title_widget -> setParent($schema_widget);
*/
    $instructions_widget = new sfWidgetFormSchema(array(),array(),array('class'=>'instruction_schema'),array(''));
    $instructions_widget -> setLabel('Degrees, Majors, Skills <span class="smaller">For each of these use the appropriate ID\'s seperated by a single space</span>'); 
     
    
    //we have to define each widget if we call setWidgets, even those not being customised
    $this->setWidgets(array(
 //     'schema'                => $schema_widget,
      'id'                  => new sfWidgetFormInputHidden(),
      //see http://www.symfony-project.org/api/1_4/sfWidgetForm#method___construct
      'title'               =>  new sfWidgetFormInputText(),//$title_widget
      'organisation'        => new sfWidgetFormTextarea(),
      'description'         => new sfWidgetFormTextarea(),
      'has_additional_info' => new sfWidgetFormInputCheckbox(),
      'has_gpa_cutoff'      => new sfWidgetFormInputCheckbox(array(),array('title'=>'This assumes a 5.0 cutoff')),
      'instructions'        => $instructions_widget,
      'degree_ids'          => new sfWidgetFormInputText(),
      'major_ids'           => new sfWidgetFormInputText(),
      'skill_set_ids'       => new sfWidgetFormInputText(),
    ));
  }
}
