<?php
// running directly?
if (empty($CFG) || $USER->id_tadmin != 1 || empty($catalog)) {
    die;
}

if (!empty($datas)) {
    foreach ($datas as $data) {
        // add/remove catalog flagged
        if (in_array($catalog, $catalogs_addremove_field) && (!empty($data->new) || !empty($data->delete))) {
            if (!empty($data->new)) {
                $rs = insert_record($catalog, $data);
            }

            elseif (!empty($data->delete)) {
                $rs = delete_records($catalog, 'id', $data->id);
            } 
        }
       
        // update record
        else {
            $rs = update_record($catalog, $data);
        }


        if (!$rs) {
            $errmsg[] = __('Hubo un error al actualizar los datos.');
        } else {
             $ok = true;
        }
    }

    if (!empty($ok)) {
        $errmsg[] = __('Datos actualizados.');
    }
}

?>
