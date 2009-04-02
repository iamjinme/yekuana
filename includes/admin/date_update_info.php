<?php
    if (empty($CFG) || Context != 'admin') {
        die; //exit
    }

    // new date
    if (empty($date->id)) {
        $rs = insert_record('fecha_evento', $date);
    } else {
        $rs = update_record('fecha_evento', $date);
    }

    if (!$rs) {
        // Fatal error
        show_error(__('No se pudo insertar/actualizar los datos.'));
    } else {
        // refresh date
        if (Action == 'newdate') {
            $date = get_record('fecha_evento', 'id', (int) $rs);
        } else {
            // updated
            $date = get_record('fecha_evento', 'id', $date->id);
        }

        include($CFG->admdir . 'date_display_info.php');
    }
?>
