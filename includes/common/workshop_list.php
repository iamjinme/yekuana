<?php
// dummy check
if (empty($CFG)) {
    die;
}

require($CFG->comdir . 'register_flag_check.php');

if (Context == 'asistente') {
    $workshops = get_events(0, 0, '', 0, true);
    $workshops_limit = count_records('inscribe', 'id_asistente', $USER->id);
}

if (!empty($workshops)) {
    // show warning if reached workshops limit
    if ($workshops_limit > $CFG->max_inscripcionTA) {
        show_error('Has llegado al límite de talleres/tutoriales inscritos.', false);
    }

    $table_data = array();

    // initialize old date;
    $last_date = '';

    if (Context == 'asistente') {
        $headers = array(__('Taller/Tutorial'), __('Orientación'), __('Lugar'), __('Hora'), __('Disp.'), '');
    }

    $table_data[] = $headers;

    foreach ($workshops as $workshop) {

        // hold date
        $current_date = $workshop->fecha;

        // check if start table
        if (!empty($last_date) && $last_date != $current_date) {
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
        $last_date_desc = $workshop->date_desc;

        if (Context == 'asistente') {
            // set session return path
            $_SESSION['return_path'] = get_url('person/workshops');

            // url ;-)
            $url = get_url('person/proposals/'.$workshop->id);

            $l_ponencia = <<< END
<ul>
<li><a class="proposal" href="{$url}">{$workshop->nombre}</a> <em class="littleinfo">({$workshop->tipo})</em>
<ul><li>
<span class="littleinfo">{$workshop->nombrep} {$workshop->apellidos}</span>
</li></ul>
</li></ul>
END;
                  
        }

        // human readable start and end hour
        $endhour = $workshop->hora + $workshop->duracion -1;
        $time = sprintf('%02d:00 - %02d:50', $workshop->hora, $endhour);

        // subscribed
        $subs = count_records('inscribe', 'id_evento', $workshop->id_evento);

        // availability
        $disp = (empty($workshop->cupo)) ? '' : $workshop->cupo-$subs;

        if (Context == 'asistente') {
            $flag = record_exists('inscribe', 'id_asistente', $USER->id, 'id_evento', $workshop->id_evento);

            $url = get_url('person/workshops/'.$workshop->id);

            if ($flag) {
                $l_action = "<a href=\"{$url}/unsubscribe\" class=\"precaucion\">". __('Dar de baja') ."</a>";
            }
            
            elseif ($workshops_limit <= $CFG->max_inscripcionTA) {
                $l_action = __('Dar de alta');
                $l_action = "<a href=\"{$url}/subscribe\" class=\"verde\">". __('Dar de alta') ."</a>";
            } else {
                $l_action = '';
            }
        }

        if (Context == 'asistente') {
            // data
            $table_data[] = array(
                $l_ponencia,
                $workshop->orientacion,
                $workshop->lugar,
                $time,
                $disp,
                $l_action
                );
        }

    }

    $human_date = friendly_date($last_date);
?>

<h2><?=$human_date ?></h2>
<h3><?=$last_date_desc ?></h2>

<?php

    // do last table
    do_table($table_data, 'wide');

} else {
?>

<div class="block"></div>

<p class="error center"><?=__('No se encontraron talleres/tutoriales registrados.') ?></p>

<?php 
}
?>
