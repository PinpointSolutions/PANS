<?php

require_once dirname(__FILE__).'/../lib/projectGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/projectGeneratorHelper.class.php';

/**
 * project actions.
 *
 * @package    PANS
 * @subpackage project
 * @author     Pinpoint Solutions
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class projectActions extends autoProjectActions
{
  // Admin tools
  // Do not modify or remove this function
  public function executeTool(sfWebRequest $request)
  {
  }
  
  public function executeClearAllStudents(sfWebRequest $request)
  {
    $conn = Doctrine_Manager::getInstance();
    $students = Doctrine_Core::getTable('StudentUser')->findAll();
    $students->delete();
    
    $this->getUser()->setFlash('notice', 'Students deleted.');
    $this->redirect('project/tool');
  }
  
  public function executeClearAllProjects(sfWebRequest $request)
  {
    $conn = Doctrine_Manager::getInstance();
    $projects = Doctrine_Core::getTable('Project')->findAll();
    $projects->delete();
    
    $this->getUser()->setFlash('notice', 'Projects deleted.');
    $this->redirect('project/tool');
  }

  public function executeListStudentsToProjects(sfWebRequest $request)
  {
    $this->getUser()->setFlash('notice', 'Students have been sorted.');
    $this->redirect('project/index');
  }
}
