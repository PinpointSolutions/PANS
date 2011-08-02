<?php

/**
 * StudentUser form.
 *
 * @package    PANS
 * @subpackage form
 * @author     Pinpoint Solutions
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class StudentUserForm extends BaseStudentUserForm
{
  public function configure()
  {
    unset(
      $this['created_at'],
      $this['updated_at'],
      $this['form_completed']
    );
	
	$snum_widget = new sfWidgetFormInputText();
	$snum_widget->setLabel('Student Number');
	
	$first_name_widget = new sfWidgetFormInputText();
	$first_name_widget->setLabel('First Name');
	
	$last_name_widget = new sfWidgetFormInputText();
	$last_name_widget->setLabel('Last Name');
	
	$pass_fail_widget = new sfWidgetFormInputCheckbox();
	$pass_fail_widget->setLabel('Please check this box if you passed Project Management: ');
	
	$majors_widget = new sfWidgetFormInputText();
	$majors_widget->setLabel('Indicate your Major/s');	
	
	$this->setWidgets(array(
		'snum' => $snum_widget,
		'first_name' => $first_name_widget,
		'last_name' => $last_name_widget,
		'pass_fail_pm' => $pass_fail_widget,
		'major_ids' => $majors_widget
		));
  }  
}
