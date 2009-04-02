<?php
// This file is taked from elgg

global $db;
global $CFG;
global $errmsg;

// messages holder
$errmsg = array();

if (!is_readable($CFG->rootdir . 'config.php')) {
    echo '<html><body>';
    echo '<table align="center"><tr>';
    echo '<td style="color:#990000; text-align:center; font-size:large; border-width:1px; '.
        '    border-color:#000000; border-style:solid; border-radius: 20px; border-collapse: collapse; '.
        '    -moz-border-radius: 20px; padding: 15px">';
    echo '<p>Error: Configuration file not found.</p>';
    echo '<p>Perhaps is not installed properly. Please read INSTALL file and go to <a href="install.php">install.php</a>.</p>';
    echo '</td></tr></table>';
    echo '</body></html>';
    die;

}

// load config
include($CFG->rootdir . 'config.php');

/// First try to detect some attacks on older buggy PHP versions
if (isset($_REQUEST['GLOBALS']) || isset($_COOKIE['GLOBALS']) || isset($_FILES['GLOBALS'])) {
    die('Fatal: Illegal GLOBALS overwrite attempt detected!');
}

// check availability of mb_* functions
if (!function_exists('mb_get_info')) {
    die("Fatal: Not found MultiByte support in your php installation");
}

// Check if file directory exists and is writable
if (!is_dir($CFG->files) || !is_writable($CFG->files)) {
    die("Fatal: Can't write in directory $CFG->files'");
}

// sanity check of files path
if (substr($CFG->files, strlen($CFG->files)-1, 1) != '/') {
    // trailing slash
    $CFG->files .= '/';
}

//for uploadlib compatibility
$CFG->dataroot = $CFG->files;

if (empty($CFG->directorypermissions)) {
    $CFG->directorypermissions = 0777;
}

if (empty($CFG->filepermissions)) {
    $CFG->filepermissions = 0777;
}

/// Just say no to link prefetching (Moz prefetching, Google Web Accelerator, others)
/// http://www.google.com/webmasters/faq.html#prefetchblock

if (!empty($_SERVER['HTTP_X_moz']) && $_SERVER['HTTP_X_moz'] === 'prefetch'){
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Prefetch Forbidden');        
    trigger_error('Prefetch request forbidden.');
    exit;
}

// Privacy policy for IE, bless its cotton socks

header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

// Set defaults for some variables

// set up our database connection
if ($CFG->debug & E_USER_ERROR) {
    include('adodb/adodb-errorhandler.inc.php');
}
require('adodb/adodb.inc.php'); // Database access functions

if (empty($CFG->dbtype)) {
    $CFG->dbtype = 'mysql';
}

$db = &ADONewConnection($CFG->dbtype);
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

error_reporting(0);  // Hide errors

if (!empty($CFG->dbpersist)) {
    $dbcmd = 'PConnect'; // Use persistent connection (default)
} else {
    $dbcmd = 'Connect'; // Use single connection
}

if (is_array($CFG->dbhost)) {
    foreach ($CFG->dbhost as $ahost) {
        if ($dbconnected = $db->$dbcmd($ahost,$CFG->dbuser,$CFG->dbpass,$CFG->dbname)) {
            $CFG->dbhost = $ahost;
            break;
        }
    }
} else {
    $dbconnected = $db->$dbcmd($CFG->dbhost,$CFG->dbuser,$CFG->dbpass,$CFG->dbname);
}

if (! $dbconnected) {
    // In the name of protocol correctness, monitoring and performance
    // profiling, set the appropriate error headers for machine consumption
    if (isset($_SERVER['SERVER_PROTOCOL'])) { 
        // Avoid it with cron.php. Note that we assume it's HTTP/1.x
        header($_SERVER['SERVER_PROTOCOL'] . ' 503 Service Unavailable');        
    }
    // and then for human consumption...
    echo '<html><body>';
    echo '<table align="center"><tr>';
    echo '<td style="color:#990000; text-align:center; font-size:large; border-width:1px; '.
        '    border-color:#000000; border-style:solid; border-radius: 20px; border-collapse: collapse; '.
        '    -moz-border-radius: 20px; padding: 15px">';
    echo '<p>Error: Database connection failed.</p>';
    echo '<p>It is possible that the database is overloaded or otherwise not running properly.</p>';
    echo '<p>The site administrator should also check that the database details have been correctly specified in config.php</p>';
    echo '</td></tr></table>';
    echo '</body></html>';
    die;
} else {
    if ($db->databaseType == 'mysql') {
        $db->Execute("SET NAMES 'utf8'");
        $db->Execute("SET CHARSET 'utf8'");
    } else if ($db->databaseType == 'postgres7') {
        $db->Execute("SET NAMES 'utf8'");
    }
}

if (empty($CFG->prefix)) {
    $CFG->prefix = '';
}
/// Set error reporting back to normal
if (empty($CFG->debug)) {
    $CFG->debug = 7;
}
error_reporting($CFG->debug);

/// Set up session handling
if(empty($CFG->respectsessionsettings)) {
    if (ini_get('session.gc_probability') == 0) {
        ini_set('session.gc_probability', 1);
    }
}

/// Configure ampersands in URLs

@ini_set('arg_separator.output', '&amp;');

/// Refuse to run with register_globals
if (ini_get_bool('register_globals')) {
    die("Cannot run with register_globals on");
}

// Now we use prepared statements everywhere,
// we want everything to be stripslashed
// rather than addslashed.
if (ini_get_bool('magic_quotes_gpc') ) {
    
    //do keys as well, cos array_map ignores them
    function stripslashes_arraykeys($array) {
        if (is_array($array)) {
            $array2 = array();
            foreach ($array as $key => $data) {
                if ($key != stripslashes($key)) {
                    $array2[stripslashes($key)] = $data;
                } else {
                    $array2[$key] = $data;
                }
            }
            return $array2;
        } else {
            return $array;
        }
    }
    
    function stripslashes_deep($value) {
        if (is_array($value)) {
            $value = stripslashes_arraykeys($value);
            $value = array_map('stripslashes_deep', $value);
        } else {
            $value = stripslashes($value);
        }
        return $value;
    }
    
    $_POST = stripslashes_arraykeys($_POST);
    $_GET = stripslashes_arraykeys($_GET);
    $_COOKIE = stripslashes_arraykeys($_COOKIE);
    $_REQUEST = stripslashes_arraykeys($_REQUEST);
    
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
    $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
    if (!empty($_SERVER['REQUEST_URI'])) {
        $_SERVER['REQUEST_URI'] = stripslashes($_SERVER['REQUEST_URI']);
    }
    if (!empty($_SERVER['QUERY_STRING'])) {
        $_SERVER['QUERY_STRING'] = stripslashes($_SERVER['QUERY_STRING']);
    }
    if (!empty($_SERVER['HTTP_REFERER'])) {
        $_SERVER['HTTP_REFERER'] = stripslashes($_SERVER['HTTP_REFERER']);
    }
    if (!empty($_SERVER['PATH_INFO'])) {
        $_SERVER['PATH_INFO'] = stripslashes($_SERVER['PATH_INFO']);
    }
    if (!empty($_SERVER['PHP_SELF'])) {
        $_SERVER['PHP_SELF'] = stripslashes($_SERVER['PHP_SELF']);
    }
    if (!empty($_SERVER['PATH_TRANSLATED'])) {
        $_SERVER['PATH_TRANSLATED'] = stripslashes($_SERVER['PATH_TRANSLATED']);
    }
    
}

//load metatables
$METATABLES = $db->Metatables();

//check db sanity
require($CFG->incdir . 'dbsetup.php');

if ($METATABLES) {
    // load config from db at end
    $CFG = get_config();
}

// Now can load gettext language based on $CFG->locale
load_default_textdomain();

// load again config.php to override db configs values
include($CFG->rootdir . 'config.php');


//try to guess correct wwwroot
if (empty($CFG->wwwroot)) {
    $path = $_SERVER['SCRIPT_NAME'];

    //remove index name
    $path = str_replace('/index.php', '', $path);

    //build url web
    $CFG->wwwroot = 'http://' . $_SERVER['SERVER_NAME'] . $path;
}

//default css stylesheet
$CFG->stylesheet =  $CFG->wwwroot . '/templates/style.css';

// set global request_uri
$request_uri = $_SERVER['REQUEST_URI'];

//prevent warning
$CFG->send_mail = (empty($CFG->send_mail)) ? false : $CFG->send_mail;

//for backward compatibility
define('SEND_MAIL', $CFG->send_mail);

function ini_get_bool ($ini_get_arg) {
    $temp = ini_get($ini_get_arg);

    if ($temp == '1' or strtolower($temp) == 'on') {
        return true;
    } else {
        return false;
    }
}

?>
