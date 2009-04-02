<?php
// dummy check
if (empty($CFG)) {
    die;
}

$date_id = 0;
$room_id = 0;
$date = '';

if (Action == 'eventsroom') {
    preg_match('#^admin/rooms/(\d+)/events$#', $q, $matches);
    $room_id = (!empty($matches)) ? (int) $matches[1] : 0;

    $room = get_record('lugar', 'id', $room_id);

    $tipo = (empty($room->cupo)) ? __('Salón para Conferencias') : __('Aula para Talleres y/o Tutoriales');

    $values = array(
        __('Nombre') => $room->nombre_lug,
        __('Ubicación') => $room->ubicacion,
        __('Tipo') => $tipo
        );

    do_table_values($values, 'narrow');
} 

elseif (Action == 'eventsdate') {
    preg_match('#^admin/dates/(\d+)/events$#', $q, $matches);
    $date_id = (!empty($matches)) ? (int) $matches[1] : 0;

    $date = get_record('fecha_evento', 'id', $date_id);

    $fecha = friendly_date($date->fecha, true);

    $values = array(
        __('Fecha') => $fecha,
        __('Descripción') => $date->descr
        );

    do_table_values($values, 'narrow');
}
    
if (Context == 'ponente') {
    $proposals = get_events($date_id, $room_id, $date, $USER->id);
} else {
    $proposals = get_events($date_id, $room_id, $date);
}

if (Context == 'admin') {
    //for admin actions
    $_SESSION['return_path'] = get_url('admin/events');
}

if (!empty($proposals)) {
    $table_data = array();

    // initialize old date;
    $last_date = '';

    if (Action == 'listevents' || Action == 'eventsdate') {
        $headers = array(__('Ponencia'), __('Tipo'), __('Hora'), __('Cupo'), __('Lugar'), '');
    }

    elseif (Action == 'eventsroom') {
        $headers = array(__('Ponencia'), __('Tipo'), __('Fecha'), __('Hora'), __('Cupo'), '');
    }

    elseif ((Context == 'asistente' || Context == 'ponente') && Action == 'viewevents') {
        $headers = array(__('Ponencia'), __('Tipo'), __('Hora'), __('Lugar'));
    }

    // table headers
    $table_data[] = $headers;

    foreach ($proposals as $proposal) {

        // hold date
        $current_date = $proposal->fecha;

        // check if start table
        if ((Action == 'listevents' || ((Context == 'asistente' || Context == 'ponente') && Action == 'viewevents')) && !empty($last_date) && $last_date != $current_date) {
            $human_date = friendly_date($last_date);
?>

<h2><?=$human_date ?></h2>
<h3><?=$last_date_desc ?></h2>

<?php
            // show table
            do_table($table_data, 'wide');

            // reset table
            $table_data = array();

            // readd table headers
            $table_data[] = $headers;
        } 

        // hold old date
        $last_date = $current_date;
        $last_date_desc = $proposal->date_desc;

        if ((Context == 'asistente' || Context == 'ponente') && Action == 'viewevents') {
            // set session return path
            if (Context == 'asistente') {
                $_SESSION['return_path'] = get_url('person/events');

                // url ;-)
                $url = get_url('person/proposals/'.$proposal->id);
                   
            } else {
                $_SESSION['return_path'] = get_url('speaker/events');

                // url ;-)
                $url = get_url('speaker/proposals/'.$proposal->id);
            }

            $l_ponencia = <<< END
<ul>
<li><a class="proposal" href="{$url}">{$proposal->nombre}</a>
</li></ul>
END;
        } else {
            // set urls
            $url = get_url('admin/proposals/'.$proposal->id);
            $urlp = get_url('admin/speakers/'.$proposal->id_ponente);

            $l_ponencia = <<< END
<ul>
<li><a class="proposal" href="{$url}">{$proposal->nombre}</a>
<ul><li>
<a class="speaker littleinfo" href="{$urlp}">{$proposal->nombrep} {$proposal->apellidos}</a>
</li></ul>
</li></ul>
END;
        }

        // human readable start and end hour
        $endhour = $proposal->hora + $proposal->duracion -1;
        $time = sprintf('%02d:00 - %02d:50', $proposal->hora, $endhour);

        // friendly date
        $human_date = friendly_date($proposal->fecha);

        if ($proposal->id_prop_tipo >= 50 && $proposal->id_prop_tipo < 100) {
            // attendes
            $url = get_url('admin/proposals/'.$proposal->id.'/persons');
            $l_asistentes = "<li class=\"admin-actions\"><a class=\"verde\" href=\"{$url}\">" . __("Asistentes") . "</a></li>";

            $attendees = count_records('inscribe', 'id_evento', $proposal->id_evento);
            $disp = $proposal->cupo - $attendees;
        } else {
            $l_asistentes = '';
            $disp = '';
        }

        // cancel
        $url = get_url('admin/events/'.$proposal->id_evento.'/cancel');
        $l_cancel = "<a class=\"precaucion\" href=\"{$url}\">" . __("Cancelar") . "</a>";

        // reprogram
        $url = get_url('admin/events/'.$proposal->id_evento);
        $l_edit = "<a class=\"verde\" href=\"{$url}\">" . __("Reprogramar") . "</a>";

        if (level_admin(2)) {
            // build menu
            $l_vmenu = <<< END
<ul class="list-vmenu">
{$l_asistentes}
<li class="admin-actions">{$l_edit}</li>
<li class="admin-actions">{$l_cancel}</li>
</ul>
END;
        } else {
            $l_vmenu = '';
        }

        if (Action == 'listevents' || Action == 'eventsdate') {
            // data
            $table_data[] = array(
                $l_ponencia,
                $proposal->tipo,
                $time,
                $disp,
                $proposal->lugar,
                $l_vmenu
                );
        }

        elseif (Action == 'eventsroom') {
            // data for eventsrooms
            $table_data[] = array(
                $l_ponencia,
                $proposal->tipo,
                $human_date,
                $time,
                $disp,
                $l_vmenu
                );
        }

        elseif ((Context == 'asistente' || Context == 'ponente') && Action == 'viewevents') {
            // data
            $table_data[] = array(
                $l_ponencia,
                $proposal->tipo,
                $time,
                $proposal->lugar,
                );
        }

    }

    if (Action == 'listevents' || ((Context == 'asistente' || Context == 'ponente') && Action == 'viewevents')) {
        $human_date = friendly_date($last_date);
?>

<h2><?=$human_date ?></h2>
<h3><?=$last_date_desc ?></h2>

<?php
    }

    // do last table
    do_table($table_data, 'wide');

} else {
?>

<div class="block"></div>

<p class="error center"><?=__('No se encontraron eventos registrados.') ?></p>

<?php 
}
?>
