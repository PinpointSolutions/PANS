Installing PANS
===============
This guide assumes a basic level of unix command line understanding and experience.

Prerequisites
-------------
The server should run some flavour of Unix (tested on Debian), and need the following programs installed:

 * Apache (or Nginx)
 * MySQL
 * PHP

Your client should have git installed to checkout the source code.  You will also need ssh to remotely login into the web server.

Administration
--------------
As the server / database admin, you should have:

 * Public directory of the webpage (e.g. /export/project/pinpoint/public_html)
 * Permission of read-write-execute of that directory
 * Permission of MySQL database login

Installation
------------
Get the source code in your local machine.

    git clone git://github.com/PinpointSolutions/PANS.git

Configure server information for deployment.  Open `config/properties.ini` and add/modify:

    [prod]
      host=<server ip here>
      port=22
      user=<username here>
      dir=<parent directory of the webpage>

For example:

    [prod]
      host=dwarf.ict.griffith.edu.au
      port=22
      user=s2674674
      dir=/export/project/pinpoint

Note that the directory must be the parent of the public directory, and not the actual public directory itself.  For example, if your webpage lives in `/srv/http/public_html`, you should use `/srv/http`.

Make sure you save the file.  Then, in the main directory of PANS, run

    php symfony project:deploy production --trace

This will do a dry run of the deployment.  If it looks good, initiate the actual synchronisation with the remote server.

    php symfony project:deploy production --trace --go

You might get a warning and an exception thrown at the end of this process.  If rsync has already reported sending complete (it looks like `sent XX bytes  received YYY bytes  ZZZ bytes/sec`, it's a false alarm and you can ignore that.

Now, SSH into the remote server.  Go to the directory where you just installed PANS.  Verify your server's PHP installation and extra support:

    php check_configuration.php

If the first test passes, it's all good. Make sure the mandatory requirement for Doctrine is installed as well.  We need to tell the system where the database is.  Run

    php symfony configure:database "mysql:host=<your database IP>;dbname=<your database name>" <MySQL username> <MySQL password> --env=production

For example:

    php symfony configure:database "mysql:host=mysql.ict.griffith.edu.au;dbname=pinpoint_db" pinpoint somepasswordyoullneverguess

Load up the database with tables.

    php symfony doctrine:build --all --and-load

Finally, clear the webpage cache, and set up permissions.

    php symfony cc
    cd cache; rm -rf *; cd ..
    php symfony project:permissions

If your webhost uses a different folder other than `web`, you will have to change it to what it uses.  Usually, `public_html`:

    mv web public_html

Go to that folder (`public_html`, for example) and find out its full path.

    cd public_html
    pwd

You will also have to tell the system where that folder is.  Open `config/ProjectConfiguration.class.php` and add this line in `setup()`:

    $this->setWebDir(<output of pwd>);

For example:

    $this->setWebDir('/export/project/pinpoint/public_html');

And we're done!  Congratulations. 
