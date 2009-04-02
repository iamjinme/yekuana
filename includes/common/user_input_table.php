<?php
//
// function to show user input info
// included on register or editing info of asistente
//

// dummy way to check if this file is loaded by the system
if (empty($CFG)) {
    die;
}

// build data table
$table_data = array();

// shared values
if (Context == 'admin' || Context == 'ponente' || Context == 'asistente') {
    // login
    if (Context == 'admin') {
        if (Action == 'editdetails' && $USER->login == 'admin') {
            
            // disable the input box for login 
            $input_data = do_get_output('do_input', array('S_login', 'text', $user->login, 'size="15" disabled="disabled"'));

        }

//        if (Action == 'newspeaker' || Action == 'newperson') {
        else {

            $input_data = do_get_output('do_input', array('S_login', 'text', $user->login, 'size="15"'));

        }
    } else {

        // cant change username if using external auth
        if (!empty($CFG->auth)) {
            $input_data = do_get_output('do_input', array('', 'text', $user->login, 'size="15" disabled="disabled"'));

            //add hidden input
            $input_data .= do_get_output('do_input', array('S_login', 'hidden', $user->login));

            //add access hash
            $access_hash = md5(time() . $login);
            //insert record
            $hash = new StdClass;
            $hash->login = $user->login;
            $hash->hash = $access_hash;
            insert_record('extauth_hash', $hash);

            //add hidden hash
            $input_data .= do_get_output('do_input', array('access_hash', 'hidden', $access_hash));

        } else {
            $input_data = do_get_output('do_input', array('S_login', 'text', $user->login, 'size="15"'));
        }
    }

    $table_data[] = array(
        __('Nombre de Usuario:') . ' *',
        $input_data,
        __(' 4 a 15 caracteres')
        );
        
    // no need password textboxes on external auth
    if (empty($CFG->auth) || (Context == 'admin' && !empty($user->id) && $user->id == 1)) {
        // password
        $input_data = do_get_output('do_input', array('S_passwd', 'password', '', 'size="15"'));

        $table_data[] = array(
            __('Contraseña:') . ' *',
            $input_data,
            __(' 6 a 15 caracteres')
            );

        // confirm password
        $input_data = do_get_output('do_input', array('S_passwd2', 'password', '', 'size="15"'));

        $table_data[] = array(
            __('Confirmación de Contraseña:') . ' *',
            $input_data,
            ''
            );
    }

    // first name
    $input_data = do_get_output('do_input', array('S_nombrep', 'text', $user->nombrep, 'size="30"'));

    $table_data[] = array(
        __('Nombre(s):') . ' *',
        $input_data,
        ''
        );

    // last name
    $input_data = do_get_output('do_input', array('S_apellidos', 'text', $user->apellidos, 'size="30"'));

    $table_data[] = array(
        __('Apellidos:') . ' *',
        $input_data,
        ''
        );
    
    //cedula
    $input_data = do_get_output('do_input', array('S_cedula', 'text', $user->cedula, 'size="15"'));

    $table_data[] = array(
        __('C&eacute;dula:') . ' *',
        $input_data,
        '');

    // email
    $input_data = do_get_output('do_input', array('S_mail', 'text', $user->mail, 'size="15"'));

    $table_data[] = array(
        __('Correo Electrónico:') . ' *',
        $input_data,
        ''
        );
}

if (Context == 'admin' && Action == 'newadmin') {
    $options = get_records('tadmin');

    $input_data = do_get_output('do_input_select', array('I_id_tadmin', $options, $user->id_tadmin));

    $table_data[] = array(
        __('Tipo administrador:') . ' *',
        $input_data
        );
}

if (Context == 'ponente' || Context == 'asistente' || Action == 'newspeaker' || Action == 'newperson') {
    // sexo
    $options = array();

    $option = new StdClass;
    $option->id = 'M';
    $option->descr = __('Masculino');

    $options[] = $option;

    $option = new StdClass;
    $option->id = 'F';
    $option->descr = __('Femenino');

    $options[] = $option;

    $input_data = do_get_output('do_input_select', array('C_sexo', $options, $user->sexo, true, '', ''));

    $table_data[] = array(
        __('Sexo:') . ' *',
        $input_data,
        ''
        );

    // organizacion
    $input_data = do_get_output('do_input', array('S_org', 'text', $user->org, 'size="15"'));

    $table_data[] = array(
        __('Organización:') . ' &nbsp;',
        $input_data,
        ''
        );

    // estudios
    $options = get_records('estudios');
    $input_data = do_get_output('do_input_select', array('I_id_estudios', $options, $user->id_estudios));

    $table_data[] = array(
        __('Estudios:') . ' *',
        $input_data,
        ''
        );
}

if (Context == 'ponente' || Action == 'newspeaker') {
    // titulo
    $input_data = do_get_output('do_input', array('S_titulo', 'text', $user->titulo, 'size="10"'));

    $table_data[] = array(
        __('Título:') . ' &nbsp;',
        $input_data,
        ''
        );

    // domicilio
    $input_data = do_get_output('do_input', array('S_domicilio', 'text', $user->domicilio, 'size="50" maxlength="255"'));

    $table_data[] = array(
        __('Domicilio:') . ' &nbsp;',
        $input_data,
        ''
        );

    // telefono
    $input_data = do_get_output('do_input', array('S_telefono', 'text', $user->telefono, 'size="15"'));

    $table_data[] = array(
        __('Teléfono:') . ' &nbsp;',
        $input_data,
        ''
        );
}

if (Context == 'asistente' || Action == 'newperson') {
    // tipo asistente
    if (Context == 'admin') {
        $options = get_records('tasistente');
    } else {
        $options = get_records_select('tasistente', 'id < 100');
    }
    $input_data = do_get_output('do_input_select', array('I_id_tasistente', $options, $user->id_tasistente));

    $table_data[] = array(
        __('Tipo de Asistente:') . ' *',
        $input_data,
        ''
        );
}

if (Context == 'ponente' || Context == 'asistente' || Action == 'newspeaker' || Action == 'newperson') {
    // ciudad
    $input_data = do_get_output('do_input', array('S_ciudad', 'text', $user->ciudad, 'size="15"'));

    $table_data[] = array(
        __('Ciudad:') . ' &nbsp;',
        $input_data,
        ''
        );

    // departamento
    $options = get_records('estado');
    $input_data = do_get_output('do_input_select', array('I_id_estado', $options, $user->id_estado));

    $table_data[] = array(
        __('Estado:') . ' *',
        $input_data,
        ''
        );

    // fecha de nacimiento
    $input_data = do_get_output('do_input_birth_select', array('I_b_day', 'I_b_month', 'I_b_year', $user->b_day, $user->b_month, $user->b_year));

    $table_data[] = array(
        __('Fecha de Nacimiento:') . ' &nbsp;',
        $input_data,
        ''
        );
}

if (Context == 'ponente' || Action == 'newspeaker') {
    // resumen curricular
    $input_data = <<< END
        <textarea name="S_resume" cols="60" rows="15">{$user->resume}</textarea>
END;

    $table_data[] = array(
        __('Resumen Curricular:') . ' &nbsp;',
        $input_data,
        ''
        );
}

// show tdata
do_table_input($table_data);
?>
