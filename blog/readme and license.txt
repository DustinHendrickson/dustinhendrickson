********************************************************************
	SPYKA WEBMASTER - HTTP://WWW.SPYKA.NET
	FREE WEB TEMPLATES AND RESOURCES FOR WEBMASTERS
********************************************************************

This is a free script by spyka webmaster (http://www.spyka.net)


SEE: http://www.spyka.net/scripts/php/miniblog for full information and documentation 
 
1. Installation
-----------------------------------------
If you haven't one already, create a new MySQL database to store the miniblog content. 

Open the config.php file stored in the includes folder with a text editor or similar. Enter the MySQL information (user, password, host, database name) as commented.

Upload the miniblog folder to your website.

Point your browser to the install.php file on your site, for example: http://www.domain.com/miniblog/install.php

This will attempt to create the required tables and enter the needed information.

Once this process is complete delete the file from your server.

Your miniblog is now ready to go! Login to your admin panel by pointing your browser to the adm directory, for example: http://www.domain.com/miniblog/adm/ 

The default password is just password. You should login and change this before anything else.

You can then begin to add posts. 

Full install guide: http://www.spyka.net/docs/miniblog


2. Customisation
-----------------------------------------
To edit how each post is outputted open template.html in the includes folder.

To edit the miniblog template simply edit index.php:

	PATH should be changed to the location of index.php relative to the miniblog folder.
	by default this is empty as index.php is in the miniblog folder.

Template variables:

	$miniblog_posts  - string - list of posts or post (if single)

	$single	- boolean - if in a single post this is TRUE else it's FALSE

	$miniblog_previous - string - html link to previous page of posts

	$miniblog_next - string - html link to next page of posts


Use these as you would any other PHP variables. See index.php for examples

Detailed styling guidelines: http://www.spyka.net/docs/miniblog


3. Licence
-----------------------------------------
This script is licensed under a Creative Commons Attribution 3.0 licence. This allows you to use, modify and distribute the script permitted the link to spyka.net REMAINS.

If you wish to remove this link you can buy a script licence for £10.00 - http://www.spyka.net/licensing for more information


4. Help and support
-----------------------------------------
You can get help and support for this script plus other web design and development tips from our webmaster forums: http://community.spyka.co.uk/forumdisplay.php?f=9


5. Other information
-----------------------------------------
Please contact us if you need more information about this script - spyka.net/contact
