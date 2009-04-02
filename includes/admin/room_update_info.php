<?php
    if (empty($CFG) || Context != 'admin') {
        die; //exit
    }

    // new room
    if (empty($room->id)) {
        $rs = insert_record('lugar', $room);
    } else {
        $rs = update_record('lugar', $room);
    }

    if (!$rs) {
        // Fatal error
        show_error(__('No se pudo insertar/actualizar los datos.'), false);
    } else {
        // refresh room
        if (Action == 'newroom') {
            $room = get_record('lugar', 'id', (int) $rs);
        } else {
            // updated
            $room = get_record('lugar', 'id', $room->id);
        }

        include($CFG->admdir . 'room_display_info.php');
    }
?>
