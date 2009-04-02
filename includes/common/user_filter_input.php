<?php
// state/departamento
//$state = get_records('estado');
$onChange = 'onChange=\'form.submit()\'';

$state = get_records_sql('SELECT D.* FROM '.$CFG->prefix.'estado D
                       JOIN '.$CFG->prefix.'ponente SP ON SP.id_estado=D.id');

$state_input = do_get_output('do_input_select', array('filter_id_estado', $state, $id_estado, true, '', 0, $onChange));

//search box
$search_input = do_get_output('do_input', array('filter_search_lastname', 'text', $search_lastname));

$table_data = array();

if (Action == 'controlpersons') {
    // person type
    $person_type = get_records_sql('SELECT TA.* FROM '.$CFG->prefix.'tasistente TA
                                JOIN '.$CFG->prefix.'asistente A ON A.id_tasistente = TA.id');
    $person_type_input = do_get_output('do_input_select', array('filter_id_tasistente', $person_type, $id_tasistente, true, '', 0, $onChange));

    $table_data[] = array('', __('Apellidos:'), __('Departamento:'), __('Tipo de asistente:'));
    $table_data[] = array(__('Filtro:'), $search_input, $state_input, $person_type_input);
} else {

     //$education = get_records('estudios');
    $education = get_records_sql('SELECT ST.* FROM '.$CFG->prefix.'estudios ST
                            JOIN '.$CFG->prefix.'ponente SP ON SP.id_estudios=ST.id');

    $education_input = do_get_output('do_input_select', array('filter_id_estudios', $education, $id_estudios, true, '', 0, $onChange));

    $table_data[] = array('', __('Apellidos:'), __('Departamento:'), __('Estudios:'));
    $table_data[] = array(__('Filtro:'), $search_input, $state_input, $education_input);
}

do_table($table_data, 'prop-filter wide');
?>
