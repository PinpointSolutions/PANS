<?php

class sfGuardFormSignin extends BasesfGuardFormSignin
{
    //this is used to generate the form for BOTH login views
  public function configure()
  { /* Ugly but works for now */
    $this->widgetSchema->setLabels(array(
        'username'    => 'ID<span style="float:right;margin-right:-5px;">S</span>',//still not a great fix, but a decent workaround
        'password'   => 'Password',
        'remember'   => 'Remember me?',
        ));
  }
}