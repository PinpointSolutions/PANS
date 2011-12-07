<!DOCTYPE html>
<html>
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title>Project Allocation and Nomination System</title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  
  <body> <!-- This is the layout.php for the student view -->
    <div id="wrapper">
      <div id="header">
        <a href="<?php echo url_for('@homepage') ?>"><div id="logo"></div></a>
        <div id="title">Project Allocation and Nomination System</div>
      </div>
      
      <div id="navbar">
        <?php if ($sf_user->isAuthenticated()): ?>
          <div class="alignleft">
            <?php echo $sf_user->getName() ?> (<?php echo $sf_user->getUsername() ?>)
          </div>
        
          <div class="alignleft action firstLeft">
              <?php echo link_to('Available Projects', 'project/index') ?>
          </div>

          <?php if (!$sf_user->isSuperAdmin()): ?>
            <div class="alignleft action">
               <?php echo link_to('Project Nomination Form', 'student/edit', 'target="_blank"') ?>
            </div>
          <?php endif; ?>
          
          <div class="alignleft action">
            <?php echo link_to('Logout', 'index.php/logout') ?>
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
