<!DOCTYPE html> <!-- Leave it as this so it is HTML5 compatible. -->
<html>
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title>Project Allocation and Nomination System</title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  
  <body> <!-- Three row style, with fixed-width content body -->
    <div id="wrapper">
      <div id="header">
        <a href="<?php echo url_for('@homepage') ?>"><div id="logo"></div></a>
        <div id="title">Project Allocation and Nomination System</div>
      </div>
      
      <div id="navbar">
        <?php if ($sf_user->isAuthenticated()): ?>
          <div class="alignleft">
            Student View: 
              <?php echo link_to('Available Projects', 'project/index') ?>
            | <?php echo link_to('Your Nomination Form', 'student/new') ?>
          </div>
          <div class="alignright">
            Welcome, <?php echo $sf_user ?>.
            | <?php echo link_to('Logout', '@sf_guard_signout') ?>
          </div>
        <?php endif; ?>
      </div>
      
      <div id="content">
        <?php echo $sf_content ?>
      </div>
      
      <div id="footer">
        Developed by Pinpoint Solutions
      </div>
    </div>
  </body>
</html>
