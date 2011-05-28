<!DOCTYPE html> <!-- Leave it as this so it is HTML5 compatible. -->
<html lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title><?php include_slot('title') ?></title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  
  <body> <!-- Three row style, with fixed-width content body -->
    <div id="wrapper">
      <div id="header">
        <div id="logo"></div>
        <div id="title">3001ICT - Project Nomination Form</div>
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
