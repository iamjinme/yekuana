<?php
if (!defined('Context') || Context != 'admin') {
    header('Location: ' . get_url());
}

// new event schedule
if (Action == 'scheduleevent') {
    preg_match('#^admin/events/schedule/(\d+)/?#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;

    if (!record_exists('evento', 'id_propuesta', $proposal_id)) {
        // new event
        $event = new StdClass;    
        $event->id_administrador = $USER->id;
        $event->id_propuesta = $proposal_id;
        $event->reg_time = strftime('%Y%m%d%H%M%S');

        $proposal = get_proposal($proposal_id);
    } 
}

// update event
else { // want to update the page
    preg_match('#^admin/events/(\d+)/?$#', $q, $matches);
    $event_id= (!empty($matches)) ? (int) $matches[1] : 0;

    //FIXME:
    if ($event_id == -1) {
        $errmsg[] = __('No puede modificar este evento. Es utilizado para reservar hora y lugar para eventos principales.');
    } else {
        $event = get_record('evento', 'id', $event_id);

        if (empty($event)) {
            $errmsg[] = __('Evento no encontrado.');
        } else {
            
            // get date/hour/room
            $event_place = get_record_sql('SELECT * FROM '.$CFG->prefix.'evento_ocupa WHERE id_evento='.$event->id.' GROUP BY id_evento');

            $event->id_fecha = (int)$event_place->id_fecha;
            $event->id_lugar = (int)$event_place->id_lugar;
            $event->hora = (int)$event_place->hora;

            //update admin
            $event->id_administrador = $USER->id;

            $proposal = get_proposal($event->id_propuesta);
        }
    }
}

if (empty($errmsg)) {
    require($CFG->admdir . 'event_optional_params.php');
}

if (Action == 'scheduleevent') {
?>

<h1><?=__('Registrar Evento') ?></h1>

<?php } else { ?>

<h1><?=__('Modificar Evento') ?></h1>

<?php
}

// process submit
if (!empty($submit) && !empty($event) && !empty($proposal) && empty($errmsg)) {
    // messages holder
    $errmsg = array();

    require($CFG->admdir . 'event_optional_params_check.php');

    if (!empty($errmsg)) {
        show_error($errmsg);
    } else {
        // insert or update propuesta
        require($CFG->admdir . 'event_update_info.php');

        if (Action == 'scheduleevent') {
?>

<p class="error center"><?=__('Evento agregado, ahora ya esta disponible para inscripción en caso de ser taller/tutorial.') ?></p>

<?php
        }

        elseif (Action == 'editevent') {
?>

<p class="error center"><?=__('Evento modificado.') ?></p>

<?php
        }
        // refresh proposal
        $proposal = get_proposal($proposal->id);

        // show proposal updated details
        include($CFG->comdir . 'prop_display_info.php');

        do_submit_cancel('', __('Continuar'), $return_url);
    }
} 

if (Action == 'scheduleevent' && empty($proposal) || !empty($errmsg)) {
    show_error($errmsg, false);
?>

<div class="block"></div>

<?php
    do_submit_cancel('', __('Regresar'), get_url('admin/events/schedule'));
}

elseif (empty($submit) || !empty($errmsg)) {
?> 

<form method="POST" action="">

    <p class="center"><em><?=__('Los campos marcados con asterisco(*) son obligatorios') ?></em></p>

<?php
    // show proposal data
    $prop_noshow_resume = true;
    include($CFG->comdir . 'prop_display_info.php');

    // show input table
    include($CFG->admdir . 'event_input_table.php');

    //back to list of acepted proposals
    //$return_url = get_url('admin/events/schedule');

    if (Action == 'scheduleevent') {
        do_submit_cancel(__('Registrar'), __('Cancelar'), $return_url);
    } else {
        do_submit_cancel(__('Guardar'), __('Regresar'), $return_url);
    }
?>

</form>

<?php
}
?>
