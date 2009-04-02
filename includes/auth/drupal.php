<?php
// drupal auth 
function drupal_user_auth ($login, $pass, $context) {
    global $errmsg;

    //settings
    $dbname = 'drupal';                 //drupal database name
    $dbuser = 'drupal';                 //drupal database user
    $dbpass = 'drupal';                 //drupal database password
    $dbhost = 'localhost';              //drupal database hostname

    $dbprefix = 'prefix_';              // drupal database prefix
    //end settings

    $dblink = mysql_connect($dbhost, $dbuser, $dbpass);

    // check conection
    if (!$dblink || !mysql_select_db($dbname)) {
        $errmsg[] = __('Ocurrió un error al conectar a la base de datos de drupal.');
        $errmsg[] = mysql_error();
        return false;

    } else {

        // get user id from users table
        $query = "SELECT uid FROM {$dbprefix}users WHERE name='{$login}' AND pass=MD5('{$pass}')";
        $rs = mysql_query($query);

        //fetch result
        $user = mysql_fetch_object($rs);
        mysql_free_result($rs);

        // got user?
        if (!empty($user->uid)) {
            return true;
        } else {
            return false;
        }
    }
}

?>
