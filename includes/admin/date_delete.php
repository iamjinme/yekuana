<?php
// dummy check
if (empty($q) || empty($CFG) || Context != 'admin') {
    die;
}

preg_match('#^admin/dates/(\d+)/delete$#', $q, $matches);
$date_id = (!empty($matches)) ? (int) $matches[1] : 0;

$date = get_record('fecha_evento', 'id', $date_id);

$submit = optional_param('submit');
?>

<h1><?=__('Eliminar fecha para eventos') ?></h1>

<?php
//check owner and status, dont delete acepted, scheduled or deleted¿?
if (!empty($date)) {

    if (empty($submit)) {
        // confirm delete
?>

<form method="POST" action="";

<?php
        include($CFG->admdir . 'date_display_info.php');
        do_submit_cancel(__('Eliminar'), __('Cancelar'), $return_url);
?>

</form>

<?php
    } else {
        // delete!
        // first update references
        $events = get_records_sql('SELECT id_evento FROM '.$CFG->prefix.'evento_ocupa WHERE id_fecha='. $date->id .' GROUP BY id_evento');

        if (!empty($events)) {
            // delete events-dates
            delete_records('evento_ocupa', 'id_fecha', $date->id);

            // update proposals
            foreach ($events as $event) {
                $proposal_id = get_field('evento', 'id_propuesta', 'id', $event->id_evento);

                if (!empty($proposal_id)) {
                    $proposal = new StdClass;
                    $proposal->id = $proposal_id;
                    //new status
                    $proposal->id_status = 5;

                    update_record('propuesta', $proposal);
                }

                //delete event
                delete_records('evento', 'id', $event->id_evento);
                //delete subscriptions
                delete_records('inscribe', 'id_evento', $event->id_evento);
            }
        }

        // finally delete date
        if (!$rs = delete_records('fecha_evento', 'id', $date->id)) {
            show_error(__('Ocurrio un error al eliminar el registro.'));
        } else {
?> 

<div class="block"></div>

<p class="center"><?=__('La fecha ha sido eliminada exitosamente.
Los espacios que ocupaban los asistentes inscritos en los talleres has sido liberados.
Las ponencias registradas relacionadas con la fecha han sido cambiadas de estado a "Aceptada" para su nueva asignación.') ?></p>

<?php 
        }

        do_submit_cancel('', __('Continuar'), $return_url);
    }

} else {
?>

<h1><?=__('Lugar no encontrado') ?></h1>

<div class="block"></div>
<p class="center"><?=__('Registros de la fecha no encontrados. Posiblemente no existan o no tengas acceso para eliminarlo.') ?></p>

<?php
    do_submit_cancel('', __('Regresar'), $return_url);
}
?>
