<?php
if (!defined('Context') || Context != 'admin') {
    header('Location: ' . get_url());
}

preg_match('#^admin/events/(\d+)/cancel#', $q, $matches);
$event_id = (!empty($matches)) ? (int) $matches[1] : 0;

if (!empty($event_id)) {
    $event = get_record('evento', 'id', $event_id);
    $proposal = get_proposal($event->id_propuesta);
}

$submit = optional_param('submit');
?>

<h1><?=__('Cancelar evento') ?></h1>

<p class="error center"><?=__('Esta acción liberará el espacio ocupado en el programa por este evento
y le asignará el status de <em>Cancelada</em>. Las inscripciones de los asistentes que pudieran
tener este evento serán eliminadas.') ?></p>

<?php
// process submit
if (Action == 'cancelevent' && !empty($submit) && !empty($event) && !empty($proposal)) {
    // messages holder
    $errmsg = array();

    //FIXME: check posible errors on delete
    //delete event slot
    $rs = delete_records('evento_ocupa', 'id_evento', $event->id);

    if (!$rs) {
        $errmsg[] = __('Ocurrió un error al eliminar los espacios ocupados.');
    } 

    //delete subscriptions
    $rs = delete_records('inscribe', 'id_evento', $event->id);

    if (!$rs) {
        $errmsg[] = __('Ocurrió un error al eliminar las suscripciones.');
    }

    //delete event
    $rs = delete_records('evento', 'id', $event->id);

    if (!$rs) {
        $errmsg[] = __('Ocurrió un error al eliminar el evento');
    }

    //update proposal status to cancel
    $prop = new StdClass;
    $prop->id = $proposal->id;
    $prop->id_status = 6; //cancel status

    $rs = update_record('propuesta', $prop);

    if (!$rs) {
        $errmsg[] = __('Ocurrió un error al cancelar la propuesta.');
    }
?>

<p class="error center"><?=__('El evento ha sido cancelado. Los espacios e suscripciones han sido liberados.') ?></p>

<div class="block"></div>

<?php

    do_submit_cancel('', __('Continuar'), $return_url);

} else {
?>

<form method="POST" action="">

<?php
    // show proposal data
    $prop_noshow_resume = true;
    include($CFG->comdir . 'prop_display_info.php');

    do_submit_cancel(__('Cancelar'), __('Regresar'), $return_url);
?>

</form>

<?php
}
?>
