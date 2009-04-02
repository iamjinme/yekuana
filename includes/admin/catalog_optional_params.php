<?php
// running directly?
if (empty($CFG) || $USER->id_tadmin != 1 || empty($catalog)) {
    die;
}

$datas = array();

$records = get_records($catalog);

//for ($i=1; $i<=$n; $i++) {
foreach ($records as $record) {

    $data = new StdClass;
    $data->id = $record->id;

    $input = optional_param($catalog.$record->id);

    if ($catalog == 'tadmin') {
        $data->tareas = $input;
        $datas[] = $data;
    }

    else {
        $data->descr = $input;

        if (empty($data->descr)) {

            // if flagged catalog, and empty descr mark to delete
            if (in_array($catalog, $catalogs_addremove_field)) {
                $data->delete = true;
            } else {
                $errmsg[] = __('No puedes dejar vacía ninguna descripción.');
            }
        }
       
        if (empty($errmsg)) {
            $datas[] = $data;
        }
    }

}

// get new option
if (in_array($catalog, $catalogs_addremove_field)) {
    $new_desc = optional_param($catalog.'-new');

    if (!empty($new_desc)) {
        $data = new StdClass;
        $data->new = true;
        $data->descr = $new_desc;

        $datas[] = $data;
    }
}

?>
