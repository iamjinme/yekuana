<?php
$onChange = 'onChange=\'form.submit()\'';

// only filter by tadmin
$admin_types = get_records('tadmin');

$admin_input = do_get_output('do_input_select', array('filter_id_tadmin', $admin_types, $id_tadmin, true, '', 0, $onChange));

$table_data = array();
$table_data[] = array('', __('Tipo:'));

$table_data[] = array(__('Filtro:'), $admin_input);

do_table($table_data, 'prop-filter wide');
?>

