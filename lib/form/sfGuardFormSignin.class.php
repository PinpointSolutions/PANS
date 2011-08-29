<?php

class sfGuardFormSignin extends BasesfGuardFormSignin
{
  public function configure()
  { /* Ugly but works for now */
    $this->widgetSchema->setLabels(array(
        'username'    => 'Student ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;S',
        'password'   => 'Password',
        'remember'   => 'Remember me?',
        ));
  }
}