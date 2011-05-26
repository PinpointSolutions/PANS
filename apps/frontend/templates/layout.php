<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    
	<title><?php include_slot('title') ?></title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
  <div id="header">
     <h1>3001ICT - Project Nomination Form</h1>
	 <div id="logo"></div>
     <!--<img id="logo" src="images/griffith_logo.png">-->
	 <span><?php ?></span>
  </div>

    <?php echo $sf_content ?>
  </body>
</html>
