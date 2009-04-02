<?php
    // running directly?
    if (empty($CFG)) {
        die;
    }

    if (Context == 'admin' || Context == 'ponente' || Context == 'asistente') {
        // shared values of all type of users
        $submit = optional_param('submit');

        if (Context == 'admin' && Action == 'editdetails' && $USER->id == 1) {
            $login = 'admin';
        } else {
            $login = strtolower(optional_param('S_login'));
        }

        $passwd = optional_param('S_passwd');
        $passwd2 = optional_param('S_passwd2');
        $nombrep = optional_param('S_nombrep');
        $apellidos = optional_param('S_apellidos');
        $cedula = optional_param('S_cedula');
        $mail = optional_param('S_mail');
    }

    if (Context == 'ponente' || Context == 'asistente' || Action == 'newspeaker' || Action == 'newperson') {
        // shared user values 
        $sexo = optional_param('C_sexo');
        $org = optional_param('S_org');
        $id_estudios = optional_param('I_id_estudios', 0, PARAM_INT);
        $ciudad = optional_param('S_ciudad');
        $id_estado = optional_param('I_id_estado', 0, PARAM_INT);
        $b_day = optional_param('I_b_day', 0, PARAM_INT);
        $b_month = optional_param('I_b_month', 0, PARAM_INT);
        $b_year = optional_param('I_b_year', 0, PARAM_INT);
    }

    if (Context == 'admin' && Action == 'newadmin') {
        $id_tadmin = optional_param('I_id_tadmin', 0, PARAM_INT);
    }

    if (Context == 'ponente' || Action == 'newspeaker') {
        // ponente values
        $titulo = optional_param('S_titulo');
        $domicilio = optional_param('S_domicilio');
        $telefono = optional_param('S_telefono');
        $resume = optional_param('S_resume');
    }
    
    if (Context == 'asistente' || Action == 'newperson') {
        // asistente values
        $id_tasistente = optional_param('I_id_tasistente', 0, PARAM_INT);

        //check for correct value
        if (Context != 'admin') {
            $id_tasistente = ($id_tasistente < 100) ? $id_tasistente : 0;
        }
    }

    // set $user object if empty
    if (empty($user) || !is_object($user)) {
        $user = new StdClass;
    }

    // load input data into $user
    $attrs = array();

    if (Context == 'admin' || Context == 'ponente' || Context == 'asistente') {
        $add_attrs = array(
            'login',
            'passwd',
            'passwd2',
            'nombrep',
            'apellidos',
            'cedula',
            'mail'
            );

        $attrs = array_merge($attrs, $add_attrs);
    }

    if (Context == 'admin' && Action == 'newadmin') {
        $attrs[] = 'id_tadmin';
    }

    if (Context == 'ponente' || Context == 'asistente' || Action == 'newspeaker' || Action == 'newperson') {
        $add_attrs = array(
            'sexo',
            'org',
            'id_estudios',
            'ciudad',
            'id_estado'
            );

        $attrs = array_merge($attrs, $add_attrs);
    }

    if (Context == 'admin' && Action == 'newadmin') {
        $attrs = array_merge($attrs, array('id_tadmin'));
    }

    if (Context == 'ponente' || Action == 'newspeaker') {
        $add_attrs = array(
            'titulo',
            'domicilio',
            'telefono',
            'resume'
            );
        $attrs = array_merge($attrs, $add_attrs);
    }

    if (Context == 'asistente' || Action == 'newperson') {
        $attrs = array_merge($attrs, array('id_tasistente'));
    }

    // fill $user attributes
    foreach ($attrs as $attr) {
        if (!empty($submit) || Action == 'register' || Action == 'newspeaker' || Action == 'newperson' || Action == 'newadmin') {
            // update values from input
            $user->$attr = $$attr;
        }
    }

    // set birthday 
    if (Context == 'asistente' || Context == 'ponente' || Action == 'newspeaker' || Action == 'newperson') {
        // first view or empty fecha_nac
        if (!empty($submit) || empty($user->fecha_nac)) {
            $user->b_year = $b_year;
            $user->b_month = $b_month;
            $user->b_day = $b_day;

            $user->fecha_nac = sprintf('%04d-%02d-%02d',
                                    (int)$b_year,
                                    (int)$b_month,
                                    (int)$b_day
                                );
        } else {
            // set dates from db value
            $user->b_year = substr($user->fecha_nac, 0, 4);
            $user->b_month = substr($user->fecha_nac, 5, 2);
            $user->b_day = substr($user->fecha_nac, 8, 2);
        }
    }
?>
