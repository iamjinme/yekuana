<?php
/*******************************************/
/*** Changes this settings to your needs ***/
/*******************************************/

//
// db settings
//

$CFG->dbname='yekuana';
$CFG->dbuser='yekuana_user';
$CFG->dbpass='yekuana_pass';
$CFG->dbhost='yekuana_host';

// default db tables prefix
$CFG->prefix = 'yekuana_';

// The place where the files from the speakers will be stored   
// The directory must be created and give the specific permissions in order to the webserver can write inside that directory

$CFG->files = '/var/www/yekuana/files';

/*******************************************/
/*******************************************/
/*******************************************/

// Set wwwroot if had problems on page style or links
// without trailing slash
// $CFG->wwwroot = 'http://servername/yekuana';

?>
