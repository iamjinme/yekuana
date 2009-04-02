<?php

global $CFG;

// Set includes dir
$CFG->rootdir = dirname(dirname(__FILE__)) . '/';
$CFG->incdir = $CFG->rootdir . 'includes/';
$CFG->admdir = $CFG->incdir . 'admin/';
$CFG->comdir = $CFG->incdir . 'common/';
$CFG->tpldir = $CFG->rootdir . 'templates/';

// includes
include($CFG->incdir . 'datalib.php');
include($CFG->incdir . 'constants.php');
include($CFG->incdir . 'displaylib.php');
include($CFG->incdir . 'infolib.php');

// l10n/gettext support
include($CFG->incdir . 'php-gettext/streams.php');
include($CFG->incdir . 'php-gettext/gettext.php');
include($CFG->incdir . 'l10n.php');

// run setup script 
require($CFG->incdir . 'setup.php');

function set_config($name, $value) {
    global $CFG;

    $CFG->$name = $value;
    
    if (get_field('datalists', 'name', 'name', $name)) {
        return set_field('datalists', 'value', $value, 'name', $name);
    } else {
        $config = new StdClass;
        $config->name = $name;
        $config->value = $value; 
        return insert_record('datalists', $config);
    }
}

function get_config($name=NULL) {
    global $CFG;
    
    if (!empty($name)) {
        return get_record('datalists', 'name', $name);
    }

    // this was originally in setup.php, duh!
    if ($configs = get_records('datalists')) {
        $localcfg = (array)$CFG;

        foreach ($configs as $config) {
            if (empty($localcfg[$config->name])) {
                $localcfg[$config->name] = $config->value;
            }
        }

        $localcfg = (object)$localcfg;
        return $localcfg;
    } else {
        // preserve $CFG if db returns nothing or error
        return $CFG;
    }
}

function get_url($path='') {
    global $CFG;

    if (empty($CFG->wwwroot)) {
        $url = $_SERVER['REQUEST_URI'];
    } else {
        $url = $CFG->wwwroot;
    }

    if (!empty($path)) {
        // using mod rewrite?
        if (empty($CFG->clean_url)) {
            $url .= '/?q=' . $path;
        } else {
            $url .= '/' . $path;
        }
    }

    return $url;
}

function clean_text($text, $format=FORMAT_MOODLE) {

    global $ALLOWED_TAGS;

    switch ($format) {
        case FORMAT_PLAIN:
            return $text;

        default:

        /// Remove tags that are not allowed
//            $text = strip_tags($text, $ALLOWED_TAGS);
            $text = strip_tags($text);
            
        /// Add some breaks into long strings of &nbsp;
            $text = preg_replace('/((&nbsp;){10})&nbsp;/', '\\1 ', $text);

        /// Remove script events
            $text = eregi_replace("([^a-z])language([[:space:]]*)=", "\\1Xlanguage=", $text);
            $text = eregi_replace("([^a-z])on([a-z]+)([[:space:]]*)=", "\\1Xon\\2=", $text);

            return $text;
    }
}

// returns particular value for the named variable taken from
// POST or GET, otherwise returning a given default.

function optional_param ($varname, $default=NULL, $options=PARAM_CLEAN) {

    if (isset($_POST[$varname])) {  // POST has precedence
        $param = $_POST[$varname];
    } else if (isset($_GET[$varname])){
        $param = $_GET[$varname];
    } else {
        return $default;
    }

    return clean_param($param, $options);
}

// clean the variables and/or cast to specific types, based on
// an options field

function clean_param ($param, $options) {

    global $CFG;

    if (is_array($param)) {              // Let's loop
        $newparam = array();
        foreach ($param as $key => $value) {
            $newparam[$key] = clean_param($value, $options);
        }
        return $newparam;
    }

    if (!$options) {
        return $param;                   // Return raw value
    }

    //this corrupts data - Sven
    //if ((string)$param == (string)(int)$param) {  // It's just an integer
    //    return (int)$param;
    //}

    if ($options & PARAM_CLEAN) {
// this breaks backslashes in user input
//        $param = stripslashes($param);   // Needed by kses to work fine
        $param = clean_text($param);     // Sweep for scripts, etc
// and this unnecessarily escapes quotes, etc in user input
//        $param = addslashes($param);     // Restore original request parameter slashes
    }

    if ($options & PARAM_INT) {
        $param = (int)$param;            // Convert to integer
    }

    if ($options & PARAM_ALPHA) {        // Remove everything not a-z
        $param = eregi_replace('[^a-zA-Z]', '', $param);
    }

    if ($options & PARAM_ALPHANUM) {     // Remove everything not a-zA-Z0-9
        $param = eregi_replace('[^A-Za-z0-9]', '', $param);
    }

    if ($options & PARAM_ALPHAEXT) {     // Remove everything not a-zA-Z/_-
        $param = eregi_replace('[^a-zA-Z/_-]', '', $param);
    }

    if ($options & PARAM_BOOL) {         // Convert to 1 or 0
        $tempstr = strtolower($param);
        if ($tempstr == 'on') {
            $param = 1;
        } else if ($tempstr == 'off') {
            $param = 0;
        } else {
            $param = empty($param) ? 0 : 1;
        }
    }

    if ($options & PARAM_NOTAGS) {       // Strip all tags completely
        $param = strip_tags($param);
    }

    if ($options & PARAM_SAFEDIR) {     // Remove everything not a-zA-Z0-9_-
        $param = eregi_replace('[^a-zA-Z0-9_-]', '', $param);
    }

    if ($options & PARAM_FILE) {         // Strip all suspicious characters from filename
        $param = ereg_replace('[[:cntrl:]]|[<>"`\|\':\\/]', '', $param);
        $param = ereg_replace('\.\.+', '', $param);
        if($param == '.') {
            $param = '';
        }
    }

    if ($options & PARAM_PATH) {         // Strip all suspicious characters from file path
        $param = str_replace('\\\'', '\'', $param);
        $param = str_replace('\\"', '"', $param);
        $param = str_replace('\\', '/', $param);
        $param = ereg_replace('[[:cntrl:]]|[<>"`\|\':]', '', $param);
        $param = ereg_replace('\.\.+', '', $param);
        $param = ereg_replace('//+', '/', $param);
        $param = ereg_replace('/(\./)+', '/', $param);
    }

    if ($options & PARAM_HOST) {         // allow FQDN or IPv4 dotted quad
        preg_replace('/[^\.\d\w-]/','', $param ); // only allowed chars
        // match ipv4 dotted quad
        if (preg_match('/(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/',$param, $match)){
            // confirm values are ok
            if ( $match[0] > 255
                 || $match[1] > 255
                 || $match[3] > 255
                 || $match[4] > 255 ) {
                // hmmm, what kind of dotted quad is this?
                $param = '';
            }
        } elseif ( preg_match('/^[\w\d\.-]+$/', $param) // dots, hyphens, numbers
                   && !preg_match('/^[\.-]/',  $param) // no leading dots/hyphens
                   && !preg_match('/[\.-]$/',  $param) // no trailing dots/hyphens
                   ) {
            // all is ok - $param is respected
        } else {
            // all is not ok...
            $param='';
        }
    }

    if ($options & PARAM_CLEANHTML) {
//        $param = stripslashes($param);         // Remove any slashes 
        $param = clean_text($param);           // Sweep for scripts, etc
//        $param = trim($param);                 // Sweep for scripts, etc
    }

    return $param;
}

// send mail
function send_mail($contactname, $contactemail, $subject, $message, $myname='', $mymail='', $bcc='', $replyto='', $replytoname='') {
    global $CFG;
    global $release;

    // needed for compatibility
    $CFG->libdir = dirname(__FILE__);

    // include mailer library
    include_once(dirname(__FILE__).'/phpmailer/class.phpmailer.php');

    $mail = new phpmailer;

    $mail->Version = 'Yupana ' . $release;
    $mail->PluginDir = $CFG->libdir . '/phpmailer/';

    $mail->CharSet = 'UTF-8';

    if (empty($CFG->smtp)) {
        $mail->IsMail();
    } else {
        $mail->IsSMTP();

        $mail->Host = $CFG->smtp;

        if (!empty($CFG->smtpuser) && !empty($CFG->smtppass)) {
            $mail->SMTPAuth = true;
            $mail->Username = $CFG->smtpuser;
            $mail->Password = $CFG->smtppass;
        }
    }

    $mail->Sender = $CFG->adminmail;

    if (!empty($myname) && !empty($mymail)) {
        $mail->From = $mymail;
        $mail->FromName = $myname;
    } elseif (!empty($myname)) {
        $mail->From = $CFG->adminmail;
        $mail->FronNAme = $myname;
    } else {
        $mail->From = $CFG->general_mail;
        $mail->FromName = $CFG->conference_name;
    }

    if (!empty($replyto) && !empty($replytoname)) {
        $mail->AddReplyTo($replyto, $replytoname);
    }

    $mail->Subject = substr(stripslashes($subject),0,900);
    $mail->AddAddress($contactemail, $contactname);

    $mail->WordWrap = 79;

    $mail->IsHTML(false);
    $mail->Body = "\n$message\n";

    if ($CFG->send_mail == 1) {
        if ($mail->Send()) {
            return true;
        } else {
            print_object($mail->ErrorInfo);
            return false;
        }
    } 

    if ($CFG->debug > 7) {
        print_object($mail);
    }
}

function request_password($login, $type) {
    global $CFG;

    if ($type == 'A') {
        $user_type = 'person';
        $sUserType = __('asistente');
        $table = 'asistente';
    } elseif ($type == 'P') {
        $user_type = 'speaker';
        $sUserType = __('ponente');
        $table = 'ponente';
    } else {
        // duh!
        return false;
    }

    $user = get_record($table, 'login', $login);

    if (empty($user)) {
        return false;
    }

    $pwreq = new StdClass;
    $pwreq->user_id = $user->id;
    $pwreq->user_type = $user_type;
    $pwreq->code = 'req' . substr(base_convert(md5(time() . $user->login), 16, 24), 0, 30);

    if (!insert_record('password_requests', $pwreq)) {
        return false;
    } else {
       $subject = sprintf(__('%s: Cambio de contraseña %s'), $CFG->conference_name, $sUserType);

       $url = get_url('recover_password/'.$pwreq->code);

       $message = "";
       $message .= sprintf(__("Has solicitado cambio de contraseña para el usuario %s\n"), $user->login);
       $message .= __("Para confirmarlo ingrese a la siguiente dirección:\n");
       $message .= "  {$url}\n\n";
       $message .= "--";
       $message .= "{$CFG->conference_name}\n";
       $message .= "{$CFG->conference_link}\n";
    }

    return send_mail($user->namep.' '.$user->apellidos, $user->mail, $subject, $message);
}

function generatePassword() {

    $salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    srand((double)microtime()*1000000);  
    $i = 0;
    $pass = '';
    while ($i < 15) {  // change for other length
        $num = rand() % 33;
        $tmp = substr($salt, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}

function strftime_caste($formato, $fecha){
// strftime por Marcos A. Botta
// $fromato: como se quiere mostrar la fecha
// $fecha: tiemestamp correspondiente a la fecha y hora que se quiere mostrar
$salida = strftime($formato,  $fecha);
	    // reemplazo meses  
	    $salida = ereg_replace("January",__("Enero"),$salida);
	    $salida = ereg_replace("February",__("Febrero"),$salida);
	    $salida = ereg_replace("March",__("Marzo"),$salida);
	    $salida = ereg_replace("April",__("Abril"),$salida);
	    $salida = ereg_replace("May",__("Mayo"),$salida);
	    $salida = ereg_replace("June",__("Junio"),$salida);
	    $salida = ereg_replace("July",__("Julio"),$salida);
	    $salida = ereg_replace("August",__("Agosto"),$salida);
	    $salida = ereg_replace("September",__("Septiembre"),$salida);
	    $salida = ereg_replace("October",__("Octubre"),$salida);
	    $salida = ereg_replace("November",__("Noviembre"),$salida);
	    $salida = ereg_replace("December",__("Diciembre"),$salida);
            // reemplazo meses cortos
	    $salida = ereg_replace("Jan",__("Ene"),$salida);
	    $salida = ereg_replace("Apr",__("Abr"),$salida);
	    $salida = ereg_replace("Aug",__("Ago"),$salida);
	    $salida = ereg_replace("Dec",__("Dic"),$salida);
	    // reemplazo di'as
	    $salida = ereg_replace("Monday",__("Lunes"),$salida);
	    $salida = ereg_replace("Tuesday",__("Martes"),$salida);
	    $salida = ereg_replace("Wednesday",__("Miércoles"),$salida);
	    $salida = ereg_replace("Thursday",__("Jueves"),$salida);
	    $salida = ereg_replace("Friday",__("Viernes"),$salida);
	    $salida = ereg_replace("Saturday",__("Sábado"),$salida);
	    $salida = ereg_replace("Sunday",__("Domingo"),$salida);
	    // reemplazo dias cortos
	    $salida = ereg_replace("Mon",__("Lun"),$salida);
	    $salida = ereg_replace("Tue",__("Mar"),$salida);
	    $salida = ereg_replace("Wed",__("Mie"),$salida);
	    $salida = ereg_replace("Thu",__("Jue"),$salida);
	    $salida = ereg_replace("Fri",__("Vie"),$salida);
	    $salida = ereg_replace("Sat",__("Sab"),$salida);
	    $salida = ereg_replace("Sun",__("Dom"),$salida);
	    // reemplazo cuando es 1 de algun mes
	    $salida = ereg_replace(" 01 de "," 1&deg; de ",$salida);
	    return $salida;
} // fin strftime_caste

function month2name ($id) {
    $result = $id;
    $id = (int) $id;

    switch ($id) {
        case  1: $result = __('Enero'); break;
        case  2: $result = __('Febrero'); break;
        case  3: $result = __('Marzo'); break;
        case  4: $result = __('Abril'); break;
        case  5: $result = __('Mayo'); break;
        case  6: $result = __('Junio'); break;
        case  7: $result = __('Julio'); break;
        case  8: $result = __('Agosto'); break;
        case  9: $result = __('Septiembre'); break;
        case 10: $result = __('Octubre'); break;
        case 11: $result = __('Noviembre'); break;
        case 12: $result = __('Diciembre'); break;
    }

    return $result;
}

function beginSession($tipo) {
    global $CFG;

    @session_start(); //ignore errors
	session_register("YACOMASVARS");
	switch ($tipo)
	{
		case 'P': 
			  $login='ponlogin';
			  $id='ponid';
			  $last='ponlast';
			  break;
		case 'A':
			  $login='asilogin';
			  $id='asiid';
			  $last='asilast';
			  break;
		case 'R':
			  $login='rootlogin';
			  $id='rootid';
			  $last='rootlast';
			  $level='rootlevel';
			  break;
	}

    // Check if $last index exists, if not set to 0
    if (!empty($_SESSION['YACOMASVARS'][$last])) {
        $last_time = $_SESSION['YACOMASVARS'][$last];
    } else {
        $last_time = 0; 
    }

	$t_transcurrido = time() - $last_time;
	$hora = 3600;

	if ($tipo == 'R')
	{
		if (empty($_SESSION['YACOMASVARS'][$login]) || empty($_SESSION['YACOMASVARS'][$id]) || 
		    empty($_SESSION['YACOMASVARS'][$level]) ||
	            ($t_transcurrido > $hora))

		{    # 1 hour exp.
            //fix: in order to get admin login
            @session_unset();
            @session_destroy();

            header('Location: ' . get_url('admin/login'));
			exit;
		}
	}
	else 
	{
		
		if (empty($_SESSION['YACOMASVARS'][$login]) || empty($_SESSION['YACOMASVARS'][$id]) || 
	            ($t_transcurrido > $hora))
		{    # 1 hour exp.
            header('Location: ' . get_url('logout'));
			exit;
		}
	}

	$_SESSION['YACOMASVARS'][$last] = time();
}

//implement user authentification
//must return user object with all attributes
function user_auth($login, $pass, $context) {
    global $CFG;

    switch ($context) {
        case 'admin':
            $user = get_admin(null, $login, $pass);
            break;
        case 'ponente':
            $user = get_speaker(null, $login, $pass);
            break;
        case 'asistente':
            $user = get_person(null, $login, $pass);
            break;
        default:
            return null;
    }

    //check for external auth
    //dont use external auth for main admin
    if (empty($user) && !empty($CFG->auth) && $login != 'admin') {
        $auth_include = $CFG->incdir . 'auth/' . $CFG->auth . '.php';

        if (is_readable($auth_include)) {
            include($auth_include);

            $auth_func = $CFG->auth . '_user_auth';

            // if external auth success
            // load user info of context 
            if (function_exists($auth_func)) {
                if ($auth_func($login, $pass, $context)) {
                    //load user info 
                    switch ($context) {
                        case 'admin':
                            $user = get_admin(null, $login);
                            break;
                        case 'ponente':
                            $user = get_speaker(null, $login);
                            break;
                        case 'asistente':
                            $user = get_person(null, $login);
                            break;
                    }
                }
            }
        }
    }

    return $user;
}

//return array with available external auth system
function user_auth_available () {
    global $CFG;

    $auths = array();
    $auth_scripts = $CFG->incdir . 'auth/*.php';

    foreach (glob($auth_scripts) as $auth) {
        preg_match('#auth/(.+)\.php$#', $auth, $matches);
        $auths[] = $matches[1];
    }

    return $auths;
}

//initial gettext code
//compatibility code
//gettext workaround
if (!function_exists('__gettext')) {
    function __gettext($s) {
        return __($s);
    }
}


// required functions for uploadlig.php, taked from elgglib.php

/**
 * Converts numbers like 10M into bytes.
 *
 * @param mixed $size The size to be converted
 * @return mixed
 */
function get_real_size($size=0) {
    if (!$size) {
        return 0;
    }
    $scan['GB'] = 1073741824;
    $scan['Gb'] = 1073741824;
    $scan['G'] = 1073741824;
    $scan['g'] = 1073741824;
    $scan['MB'] = 1048576;
    $scan['Mb'] = 1048576;
    $scan['M'] = 1048576;
    $scan['m'] = 1048576;
    $scan['KB'] = 1024;
    $scan['Kb'] = 1024;
    $scan['K'] = 1024;
    $scan['k'] = 1024;

    while (list($key) = each($scan)) {
        if ((strlen($size)>strlen($key))&&(substr($size, strlen($size) - strlen($key))==$key)) {
            $size = substr($size, 0, strlen($size) - strlen($key)) * $scan[$key];
            break;
        }
    }
    return $size;
}

/**
 * Returns the maximum size for uploading files.
 *
 * There are five possible upload limits:
 * 1. in Apache using LimitRequestBody (no way of checking or changing this)
 * 2. in php.ini for 'upload_max_filesize' (can not be changed inside PHP)
 * 3. in .htaccess for 'upload_max_filesize' (can not be changed inside PHP)
 * 4. in php.ini for 'post_max_size' (can not be changed inside PHP)
 * 5. by the limitations on the current situation (eg file quota)
 *
 * The last one is passed to this function as an argument (in bytes).
 * Anything defined as 0 is ignored.
 * The smallest of all the non-zero numbers is returned.
 *
 * @param int $maxbytes Current maxbytes (in bytes)
 * @return int The maximum size for uploading files.
 * @todo Finish documenting this function
 */
function get_max_upload_file_size($maxbytes=0) {
    global $CFG;

    if (! $filesize = ini_get('upload_max_filesize')) {
        if (!empty($CFG->absmaxuploadsize)) {
            $filesize = $CFG->absmaxuploadsize;
        } else {
            $filesize = '5M';
        }
    }
    $minimumsize = get_real_size($filesize);

    if ($postsize = ini_get('post_max_size')) {
        $postsize = get_real_size($postsize);
        if ($postsize < $minimumsize) {
            $minimumsize = $postsize;
        }
    }

    if ($maxbytes and $maxbytes < $minimumsize) {
        $minimumsize = $maxbytes;
    }

    return $minimumsize;
}

/*
 * Convert high ascii characters into low ascii
 * This code is from http://kalsey.com/2004/07/dirify_in_php/
 *
 */
function convert_high_ascii($s) {
    $HighASCII = array(
        "!\xc0!" => 'A',    # A`
        "!\xe0!" => 'a',    # a`
        "!\xc1!" => 'A',    # A'
        "!\xe1!" => 'a',    # a'
        "!\xc2!" => 'A',    # A^
        "!\xe2!" => 'a',    # a^
        "!\xc4!" => 'Ae',   # A:
        "!\xe4!" => 'ae',   # a:
        "!\xc3!" => 'A',    # A~
        "!\xe3!" => 'a',    # a~
        "!\xc8!" => 'E',    # E`
        "!\xe8!" => 'e',    # e`
        "!\xc9!" => 'E',    # E'
        "!\xe9!" => 'e',    # e'
        "!\xca!" => 'E',    # E^
        "!\xea!" => 'e',    # e^
        "!\xcb!" => 'Ee',   # E:
        "!\xeb!" => 'ee',   # e:
        "!\xcc!" => 'I',    # I`
        "!\xec!" => 'i',    # i`
        "!\xcd!" => 'I',    # I'
        "!\xed!" => 'i',    # i'
        "!\xce!" => 'I',    # I^
        "!\xee!" => 'i',    # i^
        "!\xcf!" => 'Ie',   # I:
        "!\xef!" => 'ie',   # i:
        "!\xd2!" => 'O',    # O`
        "!\xf2!" => 'o',    # o`
        "!\xd3!" => 'O',    # O'
        "!\xf3!" => 'o',    # o'
        "!\xd4!" => 'O',    # O^
        "!\xf4!" => 'o',    # o^
        "!\xd6!" => 'Oe',   # O:
        "!\xf6!" => 'oe',   # o:
        "!\xd5!" => 'O',    # O~
        "!\xf5!" => 'o',    # o~
        "!\xd8!" => 'Oe',   # O/
        "!\xf8!" => 'oe',   # o/
        "!\xd9!" => 'U',    # U`
        "!\xf9!" => 'u',    # u`
        "!\xda!" => 'U',    # U'
        "!\xfa!" => 'u',    # u'
        "!\xdb!" => 'U',    # U^
        "!\xfb!" => 'u',    # u^
        "!\xdc!" => 'Ue',   # U:
        "!\xfc!" => 'ue',   # u:
        "!\xc7!" => 'C',    # ,C
        "!\xe7!" => 'c',    # ,c
        "!\xd1!" => 'N',    # N~
        "!\xf1!" => 'n',    # n~
        "!\xdf!" => 'ss'
    );
    $find = array_keys($HighASCII);
    $replace = array_values($HighASCII);
    $s = preg_replace($find,$replace,$s);
    return $s;
}

/*
 * Cleans a given filename by removing suspicious or troublesome characters
 * Only these are allowed:
 *    alphanumeric _ - .
 *
 * @param string $string  ?
 * @return string
 */
function clean_filename($string) {
    $string = remove_accents($string);
    $string = convert_high_ascii($string);
    $string = eregi_replace("\.\.+", '', $string);
    $string = preg_replace('/[^\.a-zA-Z\d\_-]/','_', $string ); // only allowed chars
    $string = eregi_replace("_+", '_', $string);
    return $string;
}

function remove_accents($string) {
    $chars = array(
    // Decompositions for Latin-1 Supplement
    chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
    chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
    chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
    chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
    chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
    chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
    chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
    chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
    chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
    chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
    chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
    chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
    chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
    chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
    chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
    chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
    chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
    chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
    chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
    chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
    chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
    chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
    chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
    chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
    chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
    chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
    chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
    chr(195).chr(191) => 'y',
    // Decompositions for Latin Extended-A
    chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
    chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
    chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
    chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
    chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
    chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
    chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
    chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
    chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
    chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
    chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
    chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
    chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
    chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
    chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
    chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
    chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
    chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
    chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
    chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
    chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
    chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
    chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
    chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
    chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
    chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
    chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
    chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
    chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
    chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
    chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
    chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
    chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
    chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
    chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
    chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
    chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
    chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
    chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
    chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
    chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
    chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
    chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
    chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
    chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
    chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
    chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
    chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
    chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
    chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
    chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
    chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
    chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
    chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
    chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
    chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
    chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
    chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
    chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
    chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
    chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
    chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
    chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
    chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
    // Euro Sign
    chr(226).chr(130).chr(172) => 'E');
    
    $string = strtr($string, $chars);
    return $string;
}

?>
