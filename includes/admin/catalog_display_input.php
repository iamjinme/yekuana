<?php
if ($USER->id_tadmin != 1 || empty($catalog)) {
    die;
}

$table_data = array();
$datas = get_records($catalog);

if (!empty($datas)) {
    foreach ($datas as $data) {

        if ($catalog == 'tadmin') {
            $input_tareas = do_get_output('do_input', array($catalog.$data->id, 'text', $data->tareas, 'size="50"'));
            $table_data[] = array($data->id, $data->descr, $input_tareas);
        }

        else {
            $input_descr = do_get_output('do_input', array($catalog.$data->id, 'text', $data->descr, 'size="50"'));

            $table_data[] = array($data->id, $input_descr);
        }

    }

    if (in_array($catalog, $catalogs_addremove_field)) {
        // add input for new option
        $input_descr = do_get_output('do_input', array($catalog.'-new', 'text', '', 'size="50"'));

        $table_data[] = array('+', $input_descr);
    }

    do_table($table_data, 'catalog');
}
?>
