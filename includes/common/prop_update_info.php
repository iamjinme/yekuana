<?php
    if (empty($CFG) || (Context != 'admin'
        && Context != 'ponente')) {
        die; //exit
    }

    // new prop?
    if (Action == 'newproposal') {
        $proposal->reg_time = strftime('%Y%m%d%H%M%S');
    } else {
//        $proposal->id = get_field('propuesta', 'nombre', $proposal->nombre)  
//        $proposal->id = $idponencia;
    }

    // new prop
    if (empty($proposal->id)) {
        $rs = insert_record('propuesta', $proposal);
    } else {
        //revert prop_tipo and duracion if status in rejected, > acepted
        $prop_status = get_field('propuesta', 'id_status', 'id', $proposal->id);

        if ($prop_status == 3 || $prop_status > 4) {
            $proposal->id_prop_tipo = get_field('propuesta', 'id_prop_tipo', 'id', $proposal->id);
            $proposal->duracion = get_field('propuesta', 'duracion', 'id', $proposal->id);
        }

        //update record
        $rs = update_record('propuesta', $proposal);
    }

    if (!$rs) {
        // Fatal error
        show_error(__('Error Fatal: No se puedo insertar/actualizar los datos.'));
        die;
    } else {
        // refresh proposal
        if (Action == 'newproposal') {
            $proposal = get_proposal((int) $rs);
        } else {
            // updated
            $proposal = get_proposal($proposal->id);
        }
    }

    if (Action == 'newproposal') {
?>

<p><?=__('Tu propuesta de ponencia ha sido registrada.') ?></p>

<?php //include($CFG->comdir . 'new_user_send_mail.php'); ?>

<?php } else { ?>
        
<p><?=__('Tu propuesta de ponencia ha sido actualizada.') ?></p>

<?php } ?>

<p><?=__('Si tienes preguntas o la página no funciona correctamente, por favor contacta a') ?> <a href="mailto:<?=$CFG->adminmail ?>"><?=__('Administración') ?> <?=$CFG->conference_name ?></a></p>

<?php
    // refresh proposal from db
    if (!empty($proposal->id)) {
        $proposal = get_record('propuesta', 'id', $proposal->id);
    } else {
        $proposal = get_record('propuesta', 'nombre', $proposal->nombre, 'id_ponente', $proposal->id_ponente);
    }

    $proposal->ponencia = $proposal->nombre;
    $proposal->nivel = get_field('prop_nivel', 'descr', 'id', $proposal->id_nivel);
    $proposal->tipo = get_field('prop_tipo', 'descr', 'id', $proposal->id_prop_tipo);
    $proposal->orientacion = get_field('orientacion', 'descr', 'id', $proposal->id_orientacion);
    $proposal->status = get_field('prop_status', 'descr', 'id', $proposal->id_status);

    if (Context == 'admin') {
        //user login info
        $proposal->login = $login;
    }

    include($CFG->comdir . 'prop_display_info.php');
?>
