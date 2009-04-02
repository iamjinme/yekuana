<?php
// dummy way to check if this file is loaded by the system
if (empty($CFG) || Context != 'admin') {
    die;
}

// build data table
$table_data = array();

// room name
$input_data = do_get_output('do_input', array('S_nombre_lug', 'text', $room->nombre_lug, 'size="30"'));

$table_data[] = array(
    __('Nombre:') . ' *',
    $input_data,
    );

// room description
$input_data = do_get_output('do_input', array('S_ubicacion', 'text', $room->ubicacion, 'size="30"'));

$table_data[] = array(
    __('Ubicación:') . ' *',
    $input_data
    );

// room size
$input_data = do_get_output('do_input_number_select', array('I_cupo', $CFG->limite, 5, $room->cupo));
$info = '<span class="littleinfo">' . __('Vacío si es lugar para conferencias') . '</span>';

$table_data[] = array(
    __('Capacidad:') . ' &nbsp;',
    $input_data . $info
    );

// show tdata
do_table_input($table_data);
?>
