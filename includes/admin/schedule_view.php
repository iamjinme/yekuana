<?php
// dummy check
if (empty($CFG)) {
    die;
}
?>

<h1><?=__('Programa Preliminar') ?></h1>

<p class="center error"><em><?=__('* sujeto a cambios * ') ?></em></p>

<div class="block"></div>

<?php

// FIXME: ugly code

if (Context == 'admin') {
    //show all dates
    $dates = get_records('fecha_evento');
    //show all rooms
    $rooms = get_records('lugar');

    //for add/cancel event
    $_SESSION['return_path'] = get_url('admin/schedule');

    if (empty($dates)) {
        show_error(__('No se encuentra ninguna fecha registrada.'), false);
    } elseif (empty($rooms)) {
        show_error(__('No se encuentra ningún lugar registrado'), false);
    }

} else {
    // only show dates and rooms with programmed events 
    // dates
    $query = 'SELECT FE.* FROM '.$CFG->prefix.'fecha_evento FE
            JOIN '.$CFG->prefix.'evento_ocupa EO ON EO.id_fecha = FE.id ORDER BY FE.fecha';

    $dates = get_records_sql($query);

    // rooms
    $query = 'SELECT L.* FROM '.$CFG->prefix.'lugar L 
            JOIN '.$CFG->prefix.'evento_ocupa EO ON EO.id_lugar = L.id ORDER BY L.id';

    $rooms = get_records_sql($query);
}

$nrooms = sizeof($rooms);

$hours = array();

for ($h = $CFG->def_hora_ini; $h < $CFG->def_hora_fin; $h++) {
    $hour = new StdClass;
    $hour->id = $h;
    $hour->descr = sprintf('%02d:00<br/> - <br/> %02d:50', $h, $h);

    $hours[] = $hour;
}

$events = count_records('evento');

if (!empty($events)) {

    $prop_query = '
        SELECT  P.id, P.nombre, PT.descr AS tipo,
        P.duracion,
        P.id_orientacion, SP.nombrep, SP.apellidos,
        E.id AS id_evento
        FROM '.$CFG->prefix.'propuesta P 
        LEFT JOIN '.$CFG->prefix.'ponente SP ON SP.id = P.id_ponente
        LEFT JOIN '.$CFG->prefix.'prop_tipo PT ON PT.id = P.id_prop_tipo
        LEFT JOIN '.$CFG->prefix.'evento E ON E.id_propuesta = P.id
        LEFT JOIN '.$CFG->prefix.'evento_ocupa EO ON EO.id_evento = E.id
        WHERE   EO.id_fecha = ? 
            AND EO.id_lugar = ?
            AND EO.hora = ?';

    foreach ($dates as $date) {
        $human_date = friendly_date($date->fecha);
?>

<h3 class="center"><?=$human_date ?></h3>

<?php
        $table_data = array();

        $table_headers = array();
        $table_headers[] = __('Hora\Lugar');

        foreach ($rooms as $room) {
            $table_headers[] = $room->nombre_lug;
        }

        $table_data[] = $table_headers;

        foreach ($hours as $hour) {
            $table_row = array();

            $table_row[] = $hour->descr;

            foreach ($rooms as $room) {
                $proposal = get_record_sql($prop_query, array($date->id, $room->id, $hour->id));

                if (!empty($proposal)) {

                    $table_row[] = $proposal;
                } else {
                    $newprop = new StdClass;
                    $newprop->room_id = $room->id;
                    $newprop->hour = $hour->id;
                    $newprop->date_id = $date->id;

                    if (Context != 'main') {
                        $table_row[] = $newprop;
                    } else {
                        $table_row[] = '';
                    }
                }
            }
            
            $table_data[] = $table_row;
        }

        require($CFG->admdir . 'schedule_display.php');
    }


} else {
?>

<div class="block"></div>

<p class="error center"><?=__('Todavía no se tienen eventos programados.') ?></p>

<?php 
}
?>
