<?php
    // check
    if (empty($CFG)) {
        die;
    }

    // submit vars
    $submit = optional_param('submit');
    $login = strtolower(optional_param('S_login'));
    $passwd = optional_param('S_passwd');
    $exp = optional_param('e');

    // type of login?
    if (Context == 'admin') {

        $sess_id = 'rootid';
        $return_url = get_url('admin');

    } elseif (Context == 'ponente') {

        $sess_id = 'ponlogin';
        $return_url = get_url('speaker');

    } elseif (Context == 'asistente') {

        $sess_id = 'asiid';
        $return_url = get_url('person');

    } else { 
        // duh?
        die;
    }

    // Check if use has session
    @session_start();
    if (!empty($_SESSION['YACOMASVARS'][$sess_id]) && $exp != 'exp') {
        header("Location: {$return_url}");
        exit; //no needed
    }

    if (!empty($submit)) {

        if (empty($passwd) || !preg_match("/^\w{4,15}$/", $login)) {
            $errmsg[] = __("Usuario y/o contraseña no válidos. Por favor trate de nuevo.");
        } else {
            $user = user_auth($login, $passwd, Context);

            if (empty($user->id)) {
                $errmsg[] = __("Usuario y/o contraseña incorrectos. Por favor intente de nuevo o puede ingresar a") . " <a href=\"{$return_url}/reset.php\">" . __("Recuperar Contraseña") . "</a>";
            } else {
                // User ok, init session data
                @session_start(); // ignore errors
                session_register('YACOMASVARS');

                if (Context == 'admin') {
                    $_SESSION['YACOMASVARS']['rootid'] = $user->id;
                    $_SESSION['YACOMASVARS']['rootlogin'] = $user->login;
                    $_SESSION['YACOMASVARS']['rootlevel'] = $user->id_tadmin;
                    $_SESSION['YACOMASVARS']['rootlast'] = time();
                } elseif (Context == 'ponente') {
                    $_SESSION['YACOMASVARS']['ponid'] = $user->id;
                    $_SESSION['YACOMASVARS']['ponlogin'] = $user->login;
                    $_SESSION['YACOMASVARS']['ponlast'] = time();
                } elseif (Context == 'asistente') {
                    $_SESSION['YACOMASVARS']['asiid'] = $user->id;
                    $_SESSION['YACOMASVARS']['asilogin'] = $user->login;
                    $_SESSION['YACOMASVARS']['asilast'] = time();
                }

                // redirect to main menu
                header("Location: {$return_url}");
                exit;
            }
        }
    }
?>
