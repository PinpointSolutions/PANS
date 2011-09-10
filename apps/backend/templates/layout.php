<!DOCTYPE html>
<html>
  <head>
  <title>Project Allocation Admin Interface</title>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
	<?php use_stylesheet('admin.css') ?>
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
          <div class="alignleft">
            Admin View: 
              <?php echo link_to('View Projects', 'project/index') ?> |
              <?php echo link_to('View Students', 'student/index') ?>
          </div>
          <div class="alignright">
            Welcome, Admin User.
          </div>
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
