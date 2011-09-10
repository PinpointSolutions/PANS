<!DOCTYPE html>
<html>
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title>[Admin] Project Allocation and Nomination System</title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  
  <body> <!-- This layout.php is for the Admin View. -->
    <div id="wrapper">
      <div id="header">
        <a href="<?php echo url_for('@homepage') ?>"><div id="logo"></div></a>
        <div id="title">Project Allocation and Nomination System</div>
      </div>
      
      <div id="navbar">
        <?php if ($sf_user->isAuthenticated()): ?>
          <div class="alignleft">
            Admin View: 
              <?php echo link_to('View Projects', 'project/index') ?> |
              <?php echo link_to('View Students', 'student/index') ?>
          </div>
          <div class="alignright">
            <li><?php echo link_to('Users', '@sf_guard_user') ?></li>
            <li><?php echo link_to('Groups', '@sf_guard_group') ?></li>
            <li><?php echo link_to('Permissions', '@sf_guard_permission') ?></li>
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
