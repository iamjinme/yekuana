<?php
// dummy way to check if this file is loaded by the system
if (empty($CFG) || Context != 'admin') {
    die;
}

// build data table
$table_data = array();

if (Action == 'newevent') {
    //input only for event
    // name
    $input_data = do_get_output('do_input', array('S_nombre', 'text', $proposal->nombre, 'size="43" maxlength="150"'));

    $table_data[] = array(
        __('Nombre del evento:') . ' *',
        $input_data
        );

    // resumen 
    $input_data = <<< END
    <textarea name="S_resumen" cols="50" rows="5">{$proposal->resumen}</textarea>
END;

    $table_data[] = array(
        __('Descripción:') . ' *',
        $input_data,
        );

    // track
    $options = get_records('orientacion');
    $input_data = do_get_output('do_input_select', array('I_id_orientacion', $options, $proposal->id_orientacion));

    $table_data[] = array(
        __('Orientación:') . ' *',
        $input_data
        );

    // prop_tipo
    $options = get_records_select('prop_tipo', 'id > 100');
    $input_data = do_get_output('do_input_select', array('I_id_prop_tipo', $options, $proposal->id_prop_tipo));

    $table_data[] = array(
        __('Tipo de evento:') . ' *',
        $input_data
        );
}

// date name
$extra = 'style=\'width:110px;\'';
$default_date = '';

$dates = get_records_sql('SELECT id, fecha AS descr FROM '.$CFG->prefix.'fecha_evento ORDER BY descr');

//organizational events can assign to all dates
if (Action == 'newevent' || $proposal->id_prop_tipo > 100) {
    //TODO: multiple dates
//    $extra .= ' multiple=\'multiple\'';
//    $default_date = 'Todos los días';
}

if (!empty($dates)) {
    $input_data = do_get_output('do_input_select', array('I_id_fecha', $dates, $event->id_fecha, true, $default_date, 0, $extra));
} else {
    $input_data = '<em>' . __('No se encuentra ninguna fecha registrada') . '</em>';
}

$table_data[] = array(
    __('Fecha de evento:') . ' *',
    $input_data,
    );

// room
if ($proposal->id_prop_tipo < 50 || $proposal->id_prop_tipo >= 100) {
    $where = 'cupo=0';
} else {
    $where = 'cupo<>0';
}

$rooms = get_records_sql('SELECT id, nombre_lug AS descr FROM '.$CFG->prefix.'lugar WHERE '.$where.' ORDER BY nombre_lug');

$default_lugar = '';
//organizational events can assign to no room
if (Action == 'newevent' || $proposal->id_prop_tipo > 100) {
//    $default_lugar = 'Exteriores';
}

if (!empty($rooms)) {
    $input_data = do_get_output('do_input_select', array('I_id_lugar', $rooms, $event->id_lugar, true, $default_lugar));
} else {
    $input_data = '<em>' . __('No se encuentra ningún lugar registrado') . '</em>';
}

$table_data[] = array(
    __('Lugar de evento:') . ' *',
    $input_data
    );

// hour
//$input_data = do_get_output('do_input_number_select', array('I_hora', $CFG->def_hora_ini, $CFG->def_hora_fin-1, $event->hora));
$input_data = do_get_output('do_input_time_slot', array('I_hora', $CFG->def_hora_ini, $CFG->def_hora_fin-1, $event->hora, $proposal->duracion));

$table_data[] = array(
    __('Hora:') . ' *',
    $input_data
    );

if (Action == 'newevent') {
    // duracion
    $input_data = do_get_output('do_input_number_select', array('I_duracion', 1, 4, $proposal->duracion, true, '', 0, true));

    $table_data[] = array(
        __('Duración:') . ' *',
        $input_data,
        );
}

// show extraordinary option to magistral conference and organizational events
// DISABLED
if (false && (Action == 'newevent' || $proposal->id_prop_tipo >= 100)) {
    // extraordinario
    $selected = (empty($extraordinario)) ? 0 : 1;
    $input_data = do_get_output('do_input_yes_no', array('I_extraordinario', $selected));
    $desc = '<span class="littleinfo">' . __('Selecciona "Si" para reservar la hora en todos los lugares.') . '</span>';

    $table_data[] = array(
        __('Evento extraordinario:') . ' *',
        $input_data.$desc
        );
}

// show tdata
do_table_input($table_data, 'left');
?>
