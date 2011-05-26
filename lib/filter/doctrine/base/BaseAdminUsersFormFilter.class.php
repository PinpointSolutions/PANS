<?php

/**
 * AdminUsers filter form base class.
 *
 * @package    PANS
 * @subpackage filter
 * @author     Daniel Brose
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseAdminUsersFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'p_word'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'p_word'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('admin_users_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'AdminUsers';
  }

  public function getFields()
  {
    return array(
      'username' => 'Text',
      'p_word'   => 'Text',
    );
  }
}
