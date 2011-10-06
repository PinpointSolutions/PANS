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
  // Admin tools page
  // Do not modify or remove this function
  public function executeTool(sfWebRequest $request)
  {
    $this->email = $this->getUser()->getGuardUser()->getEmailAddress();

    try {
      $this->deadline = Doctrine_Core::getTable('NominationRound')
        ->createQuery('a')
        ->fetchOne();
    } catch (Exception $e) {
      $this->deadline = null;
    }

    if (!$this->deadline) {
      $this->deadline = 'YYYY-MM-DD';
    } else {
      $this->deadline = $this->deadline->getDeadline();
    }
  }
  
  // Admin View for the Group Page
  public function executeGroup(sfWebRequest $request)
  {
    $this->groups = Doctrine_Core::getTable('ProjectAllocation')
      ->createQuery('a')
      ->execute();
  }
  
  // Export Project table to CSV file
  public function executeExportProjects(sfWebRequest $request)
  {
    // We need some code here that calls information from the database.
    // get connection
    // go to projects table
    // then 'recall' information or some such thing.
    $conn = Doctrine_Manager::getInstance();
    $projects = Doctrine_Core::getTable('Project')->findAll();
   
    foreach($projects as $r) {
      //update formatting to be easier to treat, for example escapeSlashes to stop injection
      echo $r['id'] . "\n";
      echo $r['title'] . "\n";
      echo $r['organisation'] . "\n";
      echo $r['description'] . "\n";
      echo $r['has_additional_info'] . "\n";
      echo $r['has_gpa_cutoff'] . "\n";
      echo $r['major_ids'] . "\n";
      echo $r['skill_set_ids'] . "\n \n";           
    }

    $this->setlayout('csv');

    $this->getResponse()->clearHttpHeaders();
    $this->getResponse()->setHttpHeader('Content-Type', 'application/vnd.ms-excel');
    //maybe add timestamp to the filename
    $this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename=PANS_projectList.csv');

    // Redirecting seems to break the download.  In this case, probably no 
    // flash is better.
    // A redirect is needed to show the flash
    // $this->getUser()->setFlash('notice', 'Projects exported.');
    // $this->redirect('project/tool');
  }
  
  // Delete all projects in the database
  public function executeClearAllProjects(sfWebRequest $request)
  {
    $conn = Doctrine_Manager::getInstance();
    $projects = Doctrine_Core::getTable('Project')->findAll();
    $projects->delete();
    
    $this->getUser()->setFlash('notice', 'Projects deleted.');
    $this->redirect('project/tool');
  }
  
  // Change the deadline of the nomination round
  public function executeChangeDeadline(sfWebRequest $request)
  {
    try {
      // PHP Erro handling is really, really horrible
      $deadline = DateTime::createFromFormat('Y-m-d', $request->getPostParameter('deadline'));
      if ($deadline == false)
        throw new Exception();
    } catch (Exception $e) {
      $this->getUser()->setFlash('error', 'Invalid date. Please use YYYY-MM-DD.');
      $this->redirect('project/tool');
    }

    $conn = Doctrine_Manager::getInstance();
    $round = Doctrine_Core::getTable('NominationRound')->findAll();
    try {
      $round->delete();
    } catch (Exception $e) {}
    
    $round = new NominationRound();
    $round->setDeadline($deadline->format('Y-m-d'));
    $round->save();

    $this->getUser()->setFlash('notice', 'New date applied.');
    $this->redirect('project/tool');
  }
}
