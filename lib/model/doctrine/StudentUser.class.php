<?php

/**
 * StudentUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    PANS
 * @subpackage model
 * @author     Pinpoint Solutions
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class StudentUser extends BaseStudentUser
{
  public function save(Doctrine_Connection $conn = null)
  {
    return parent::save($conn);
  }

  /* Autocompletion selector.
   * $q is the query, partially entered name.
   * $limit is the maximum number of results we return.
   */
  static public function retrieveForSelect($q, $limit)
  {
    $criteria = new Criteria();
    // $criteria = add(StudentUser)
    return array(2222222 => 'Test Subject', 11111 => 'Mrraa');
  }
  
  /* 
   * Override the default guesses and displays the student name by first 
   * and last name.
   */
  public function __toString()
  {
    try
    {
      return (string) $this->get('first_name') . ' ' . (string) $this->get('last_name');
    } catch (Exception $e) {}
    
    return sprintf('Error, see StudentUser.class.php for "%s"', $this->getTable()->getComponentName());
  }
}
