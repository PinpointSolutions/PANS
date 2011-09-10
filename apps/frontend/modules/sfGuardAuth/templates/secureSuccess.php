<?php use_helper('I18N') ?>

<h2><?php echo __('Oops! The page you requested requires a log-in.', null, 'sf_guard') ?></h2>

<p><?php echo sfContext::getInstance()->getRequest()->getUri() ?></p>

<h3><?php echo __('Please login to continue.', null, 'sf_guard') ?></h3>

<?php echo get_component('sfGuardAuth', 'signin_form') ?>