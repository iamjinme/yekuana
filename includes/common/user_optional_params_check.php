<?php
    // halt if running directly
    if (empty($CFG)) {
        die;
    }

    // set database table
    if (Context == 'admin') {
        $dbtable = 'administrador';

        // actualizar
        if (!empty($idadmin)) {
            $userid = $idadmin;
        }
 
    }

    if (Context == 'ponente') {
        $dbtable = 'ponente';

        // actualizar
        if (!empty($idponente)) {
            $userid = $idponente;
        }
    } 
    
    if (Context == 'asistente') {
        $dbtable = 'asistente';

        // actualizar
        if (!empty($idasistente)) {
            $userid = $idasistente;
        }
    }

    // check submit value
    if (Context == 'admin' || Context == 'ponente' || Context == 'asistente') {
        // Verificar si todos los campos obligatorios no estan vacios
        if (empty($login)
            || empty($nombrep)
            || empty($apellidos)
            || empty($cedula)
            || (Context != 'admin' && empty($sexo))
            || (Context != 'admin' && empty($id_estudios))
            || (Context != 'admin' && empty($id_estado))
            || (Context == 'admin' && Action == 'newadmin' && empty($id_tadmin))
            || (Context == 'asistente' && empty($id_tasistente))) { 

            $errmsg[] = __("Verifica que los datos obligatorios los hayas introducido correctamente.");
        }

        // main admin cant be changed
        if (Context == 'admin' && Action == 'editdetails' && $USER->id == 1 && $login != 'admin') {
            $errmsg[] = __("No puedes cambiar el usuario del administrador principal.");
        }

        // users can't use admin username or similar
        if (Context != 'admin' && preg_match('#^admin.*?#', $login)) {
            $errmsg[] = __("El nombre de usuario que elegiste se encuentra reservado. Por favor elige otro.");
        }
 
        if (!preg_match("/.+\@.+\..+/",$mail)) {
            $errmsg[] = __("El correo electrónico no es válido");
        }

        // Verifica que el login sea de al menos 4 caracteres
        if (!preg_match("/^\w{4,15}$/",$login)) {
            $errmsg[] = __("El login que elijas debe tener entre 4 y 15 caracteres.");
        }

        // no need to check passwords on external auth
        // FIXME: dont trust submit var
        if ((empty($CFG->auth) && (Action == 'register' || Action == 'newspeaker' || Action == 'newadmin')) || (Action == 'editdetails' && !empty($passwd))) {

            // Verifica que el password sea de al menos 6 caracteres
            if (!preg_match("/^.{6,15}$/",$passwd)) {
                $errmsg[] = __("El password debe tener entre 6 y 15 caracteres.");
            }

            // Verifica que el password usado no sea igual al login introducido por seguridad
            elseif ($passwd == $login) {
                $errmsg[] = __("El password no debe ser igual a tu login.");
            }

            // Verifica que los password esten escritos correctamente para verificar que
            // la persona introducjo correcamente el password que eligio.
            if ($passwd != $passwd2) {
                $errmsg[] = __("Los passwords no concuerdan.");
            }
        }

        // Si no hay errores verifica que el login no este ya dado de alta en la tabla
        if (empty($errmsg)) {
            $testuser = get_record($dbtable, 'login', $login);

            // FIXME: dont trust submit var
            if (!(empty($testuser))
                && ((Action == 'register' || Action == 'newspeaker' || Action == 'newadmin')
                    || (Action == 'editdetails'
                        && $testuser->id != $USER->id))) {
                $errmsg[] = __('El usuario que elegiste ya ha sido tomado; por favor elige otro');
            }

            // If unique_mail true, check user mail in db
            if (!empty($CFG->unique_mail) && Action != 'editdetails') {
                if (record_exists($dbtable, 'mail', $mail)) {
                    $errmsg[] = __('El correo electrónico que elegiste ya ha sido registrado.');
                }
            }
        }
    }

?>
