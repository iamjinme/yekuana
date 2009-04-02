<?php
    if (empty($CFG) || (Context != 'admin'
        && Context != 'ponente' && Context !='asistente')) {
        die; //exit
    }

    switch (Context) {
    case 'admin':
        if (Action == 'newspeaker') {
            $dbtable = 'ponente';
        }

        elseif (Action == 'newperson') {
            $dbtable = 'asistente';
        }

        else {
            $dbtable = 'administrador';
        }

        break;
    case 'ponente':
        $dbtable = 'ponente';
        break;
    case 'asistente':
        $dbtable = 'asistente';
        break;
    }

    // new user or updating password?
    if (!empty($passwd)) {
        $user->passwd = md5($passwd);
    } else {
        //destroy var, prevent to update
        unset($user->passwd);
    }

    //force passwd value on external auth
    if (!empty($CFG->auth) && (Context != 'admin' || $user->id != 1)) {
        $user->passwd = '!!';
    }

    if (Context == 'admin' && Action == 'newadmin') {
        $user->id_tadmin = $id_tadmin;
    }

    // shared values of asistentes and ponentes
    if (Context == 'ponente' || Context == 'asistente') {
        $user->cedula = $cedula;
        $user->sexo = $sexo;
        $user->ciudad = $ciudad;
        $user->org = $org;
        $user->fecha_nac = sprintf('%04d-%02d-%02d', $user->b_year, $user->b_month, $user->b_day);
        $user->id_estudios = $id_estudios;
        $user->id_estado = $id_estado;

        if (Action == 'register' || Action == 'newperson' || Action == 'newspeaker') {
            $user->reg_time = strftime('%Y%m%d%H%M%S');
        }
    }

    // ponente only values
    if (Context == 'ponente') {
        $user->titulo = $titulo;
        $user->domicilio = $domicilio;
        $user->telefono = $telefono;
        $user->resume = $resume;
    }

    // asistente only values
    if (Context == 'asistente') {
        $user->id_tasistente = $id_tasistente;
    }

    if (Action == 'register' || Action == 'newspeaker' || Action == 'newperson' || Action == 'newadmin') {
        // insert new record
        $rs = insert_record($dbtable, $user);
        $user->id = (int) $rs;
    } else {
        // update record
        $rs = update_record($dbtable, $user);
    }

    if (!$rs) {
        // Fatal error
        show_error(__('Error Fatal: No se puedo insertar/actualizar los datos.'));
        die;
    }

    if (Action == 'register') {
?>

<p><?=__('Gracias por darte de alta, ahora ya podrás acceder a tu cuenta.') ?></p>

<?php include($CFG->comdir . 'new_user_send_mail.php'); ?>

<?php } else { ?>
        
<p><?=__('Información actualizada.') ?></p>

<?php } ?>

<p><?=__('Si tienes preguntas o la página no funciona correctamente, por favor
contacta a') ?> <a href="mailto:<?=$CFG->adminmail ?>"><?=__('Administración') ?> <?=$CFG->conference_name ?></a></p>

<?php
    // refresh user data
    $user = get_record($dbtable, 'id', $user->id);
    include($CFG->comdir . 'user_display_info.php');
?>
