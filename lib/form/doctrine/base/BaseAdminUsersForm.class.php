<?php

/**
 * AdminUsers form base class.
 *
 * @method AdminUsers getObject() Returns the current form's model object
 *
 * @package    PANS
 * @subpackage form
 * @author     Daniel Brose
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseAdminUsersForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'username' => new sfWidgetFormInputHidden(),
      'p_word'   => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'username' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('username')), 'empty_value' => $this->getObject()->get('username'), 'required' => false)),
      'p_word'   => new sfValidatorString(array('max_length' => 64)),
    ));

    $this->widgetSchema->setNameFormat('admin_users[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'AdminUsers';
  }

}
