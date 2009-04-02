<?php
// dummy way to check if this file is loaded by the system
if (empty($CFG) || Context != 'admin') {
    die;
}

// build data table
$table_data = array();

$year = substr($date->fecha, 0, 4);
$month = substr($date->fecha, 5, 2);
$day = substr($date->fecha, 8, 2);

$startyear = (int) strftime('%Y');

// date name
$input_data = do_get_output('do_input_date_select', array('I_e_day', 'I_e_month', 'I_e_year', $day, $month, $year, $startyear, $startyear+2));

$table_data[] = array(
    __('Fecha de evento:') . ' *',
    $input_data,
    );

// date description
$input_data = do_get_output('do_input', array('S_descr', 'text', $date->descr, 'size="30"'));

$table_data[] = array(
    __('Descripción:') . ' &nbsp;',
    $input_data
    );

// show tdata
do_table_input($table_data);
?>
