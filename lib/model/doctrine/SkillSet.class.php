<?php

/**
 * SkillSet
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    PANS
 * @subpackage model
 * @author     Pinpoint Solutions
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class SkillSet extends BaseSkillSet
{
  /* 
   * Override the default guesses and displays the full skillset name
   */
  public function __toString()
  {
    try
    {
      return (string) $this->get('area');
    } catch (Exception $e) {}
    
    return sprintf('Error, see Major.class.php for "%s"', $this->getTable()->getComponentName());
  }
}