<?php
if (empty($CFG) || empty($CFG->auth)) {
    die;
}

if (!defined('Context')
    || (Context != 'ponente'
        && Context != 'asistente')) {

    header('Location: ' . get_url());
}

// messages holder
$errmsg = array();

// setup
$submit = optional_param('submit');
$login = strtolower(optional_param('S_login'));
$passwd = optional_param('S_passwd');
$access_hash = optional_param('access_hash');

if (!empty($login)) {
    
    $auth_include = $CFG->incdir . 'auth/' . $CFG->auth . '.php';
    if (is_readable($auth_include)) {
        include($auth_include);

        $auth_func = $CFG->auth . '_user_auth';

        if (function_exists($auth_func)) {
            // initial user check with external auth
            if (!empty($passwd)) {
                $auth_flag = $auth_func($login, $passwd, Context);
            } else {
                // now register user settings, check user with hidden pw hash
                if (!empty($access_hash)) {
                    $auth_flag = record_exists('extauth_hash', 'login', $login, 'hash', $access_hash);

                    // delete used hash
                    delete_records('extauth_hash', 'login', $login, 'hash', $access_hash);
                }
            }
        } else {
            $errmsg[] = __('No se puede usar la autentificación externa. Por favor contacte al administrador.');
        }

        if (empty($auth_flag)) {
            $errmsg[] = __('Usuario y/o contraseña incorrecto.');
        } else {
            //check if user exists
            switch (Context) {
                case 'ponente':
                    $dbtable = 'ponente';
                    break;
                case 'asistente':
                    $dbtable = 'asistente';
                    break;
            }

            if (record_exists($dbtable, 'login', $login)) {
                $errmsg[] = __('El usuario ya ha sido dado de alta.');
                // destroy auth_flag
                unset($auth_flag);
            }
        }

    } else {
        $errmsg[] = __('No se puede usar la autentificación externa. Por favor contacte al administrador.');
    }
}

if (empty($auth_flag)) {

    if (Context == 'ponente') {
?>

<h1><?=__('Registro de Ponente') ?></h1>

<?php } elseif (Context == 'asistente') { ?>

<h1><?=__('Registro de Asistente') ?></h1>

<?php } ?>

<p class="center"><?=__('El sistema se encuentra utilizando autentificación externa. Por favor ingrese su usuario y contraseña para habilitarlo.') ?></p>

<?php
    if (!empty($errmsg)) {
        show_error($errmsg);
    }

    require($CFG->comdir . 'display_login_form.php');
} else {
    require($CFG->comdir . 'user_edit.php');
}
?>
