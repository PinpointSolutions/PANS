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
      <div id="header" class="admin-header">
        <a href="<?php echo url_for('@homepage') ?>"><div id="logo"></div></a>
        <div id="title">Project Allocation and Nomination System</div>
      </div>
      
      <div id="navbar" class="admin-navbar">
        <?php if ($sf_user->isAuthenticated()): ?>
        
          <div class="alignleft">
            <?php echo $sf_user->getName() ?>
          </div>
        
          <div class="alignleft action firstLeft">
           <?php echo link_to('Students', 'student/index') ?>
          </div>   

          <div class="alignleft action">
            <?php echo link_to('Degrees', 'degree/index') ?>
          </div>
          
          <div class="alignleft action">
            <?php echo link_to('Majors', 'major/index') ?>
          </div>
          
          <div class="alignleft action">
            <?php echo link_to('Skills', 'skill/index') ?>
          </div>
          
          <div class="alignleft action">
            <?php echo link_to('Projects', 'project/index') ?>
          </div>
          
          <div class="alignleft action">
            <?php echo link_to('Groups', 'project/group') ?>
          </div>
          
          <div class="alignleft action">
            <?php echo link_to('Users', 'sfGuardUser/index') ?>
          </div>

          <div class="alignleft action">
            <?php echo link_to('Tools', 'project/tool') ?>
          </div>

          <div class="alignleft">
            <?php echo link_to('Logout', '@sf_guard_signout') ?>
          </div>

        <?php endif; ?>
      </div>
      
      <div id="content" class="admin-content">
        <?php echo $sf_content ?>
      </div>
      
      <div id="footer" class="admin-footer">
        Developed by Pinpoint Solutions
      </div>
    </div>
  </body>
</html>
