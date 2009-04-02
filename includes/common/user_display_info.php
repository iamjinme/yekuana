<?php
    // running from system?
    if (empty($CFG)) {
        die;
    }

    // extra values
    if (Context == 'ponente'
        || Context == 'asistente'
        || Action == 'viewperson'
        || Action == 'viewspeaker'
        || Action == 'newspeaker'
        || Action == 'newperson'
        || Action == 'editspeaker'
        || Action == 'editperson'
        || Action == 'deletespeaker'
        || Action == 'deleteperson') {

        $estudios = get_field('estudios', 'descr', 'id', $user->id_estudios);
        $estado = get_field('estado', 'descr', 'id', $user->id_estado);
        $sexo = '';
        if ($user->sexo == 'F') {
            $sexo = __('Femenino');
        }
        if ($user->sexo == 'M') {
            $sexo = __('Masculino');
        }
    }

    if (Context == 'asistente'
        || Action == 'viewperson'
        || Action == 'newperson'
        || Action == 'deleteperson'
        || Action == 'editperson') {

        $tasistente = get_field('tasistente', 'descr', 'id', $user->id_tasistente);
    }

    // user values
    if (Context == 'admin'
        && (Action == 'editdetails' || Action == 'newadmin' || Action == 'deleteadmin' || Action == 'editadmin' || Action == 'viewadmin')) {

        $tadmin = get_field('tadmin', 'descr', 'id', $user->id_tadmin);

        $values = array(
            __('Usuario Administrador') => $user->login,
            __('Nombre(s)') => $user->nombrep,
            __('Apellidos') => $user->apellidos,
            __('Correo electrónico') => $user->mail,
            __('Tipo administrador') => $tadmin
            );
    }

    if (Context == 'ponente'
        || Action == 'viewspeaker'
        || Action == 'newspeaker'
        || Action == 'deletespeaker'
        || Action == 'editspeaker') {

        $values = array(
            __('Nombre de Usuario') => $user->login,
            __('Nombre(s)') => $user->nombrep,
            __('Apellidos') => $user->apellidos,
            __('C&eacute;dula') => $user->cedula,
            __('Correo electrónico') => $user->mail,
            __('Sexo') => $sexo,
            __('Organización') => $user->org,
            __('Estudios') => $estudios,
            __('Título') => $user->titulo,
            __('Domicilio') => $user->domicilio,
            __('Telefono') => chunk_split($user->telefono, 2),
            __('Ciudad') => $user->ciudad,
            __('Estado') => $estado,
            __('Fecha de Nacimiento') => sprintf('%s', $user->fecha_nac),
            __('Resumen Curricular') => nl2br(htmlspecialchars($user->resume))
        );
    }

    if (Context == 'asistente'
        || Action == 'viewperson'
        || Action == 'newperson'
        || Action == 'editperson'
        || Action == 'deleteperson') { // should be asistente
        if (defined('SubContext') && SubContext == 'kardex') {
            $values = array(
                __('Nombre de Usuario') => $user->login,
                __('Correo Electrónico') => $user->mail,
                __('Sexo') => $sexo,
                __('Organización') => $user->org,
                __('Estudios') => $estudios,
                __('Tipo Asistente') => $tasistente,
                __('Ciudad') => $user->ciudad,
                __('Estado') => $estado
                );
        } else {
            $values = array(
                __('Nombre de Usuario') => $user->login,
                __('Nombre(s)') => $user->nombrep,
                __('Apellidos') => $user->apellidos,
                __('C&eacute;dula') => $user->cedula,
                __('Correo electrónico') => $user->mail,
                __('Sexo') => $sexo,
                __('Organización') => $user->org,
                __('Estudios') => $estudios,
                __('Tipo de Asistente') => $tasistente,
                __('Ciudad') => $user->ciudad,
                __('Estado') => $estado,
                __('Fecha de Nacimiento') => sprintf('%s', $user->fecha_nac)
                );
        }
    }
   
    // show table with values
    do_table_values($values, 'narrow');

    // show reg/act time
    if (Context == 'admin' && (Action != 'newadmin' && Action != 'editadmin' && Action != 'editdetails')) {
        $values = array(
            __('Fecha de registro') => $user->reg_time,
            __('Fecha de actualización') => $user->act_time
            );

        do_table_values($values, 'narrow');
    }
?>
