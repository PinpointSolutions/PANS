<?php

/**
 * prefs actions.
 *
 * @package    PANS
 * @subpackage prefs
 * @author     Daniel Brose
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class prefsActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->majors = Doctrine_Core::getTable('StudentPrefs')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->student_prefs = Doctrine_Core::getTable('StudentPrefs')->find(array($request->getParameter('snum'),
                                        $request->getParameter('nomination_round')));
    $this->forward404Unless($this->student_prefs);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new StudentPrefsForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new StudentPrefsForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($student_prefs = Doctrine_Core::getTable('StudentPrefs')->find(array($request->getParameter('snum'),
                  $request->getParameter('nomination_round'))), sprintf('Object student_prefs does not exist (%s).', $request->getParameter('snum'),
                  $request->getParameter('nomination_round')));
    $this->form = new StudentPrefsForm($student_prefs);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($student_prefs = Doctrine_Core::getTable('StudentPrefs')->find(array($request->getParameter('snum'),
                  $request->getParameter('nomination_round'))), sprintf('Object student_prefs does not exist (%s).', $request->getParameter('snum'),
                  $request->getParameter('nomination_round')));
    $this->form = new StudentPrefsForm($student_prefs);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($student_prefs = Doctrine_Core::getTable('StudentPrefs')->find(array($request->getParameter('snum'),
                  $request->getParameter('nomination_round'))), sprintf('Object student_prefs does not exist (%s).', $request->getParameter('snum'),
                  $request->getParameter('nomination_round')));
    $student_prefs->delete();

    $this->redirect('prefs/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $student_prefs = $form->save();

      $this->redirect('prefs/edit?snum='.$student_prefs->getSnum().'&nomination_round='.$student_prefs->getNominationRound());
    }
  }
}
