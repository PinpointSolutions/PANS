<?php

require_once dirname(__FILE__).'/../lib/studentGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/studentGeneratorHelper.class.php';

/**
 * student actions.
 *
 * @package    PANS
 * @subpackage student
 * @author     Pinpoint Solutions
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class studentActions extends autoStudentActions
{
  // Import Student From File in disguise
  // Do not rename, remove, or modify this method
  public function executeShow(sfWebRequest $request)
  {
  }
  
  // Manually handling the file upload and parsing
  public function executeImportStudents(sfWebRequest $request)
  {
    if (!is_uploaded_file($_FILES['studentFile']['tmp_name'])) {
      $this->getUser()->setFlash('error', 'File failed to upload. Please try again.');
      $this->redirect('student/index');
    }
    
    $this->getUser()->setFlash('notice', 'Students successfully imported.');
    $this->redirect('student/index');
  }
}
