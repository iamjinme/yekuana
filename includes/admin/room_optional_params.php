<?php
    // running directly?
    if (empty($CFG) || Context != 'admin') {
        die;
    }

    // Common values
    $submit = optional_param('submit');

    $nombre_lug = optional_param('S_nombre_lug');
    $ubicacion = optional_param('S_ubicacion');
    $cupo = optional_param('I_cupo', 0, PARAM_INT);

    if (!empty($submit) || Action == 'newroom') {
        $room->nombre_lug = $nombre_lug;
        $room->ubicacion = $ubicacion;
        $room->cupo = $cupo;

        $room->nombre_lug = mb_convert_case($nombre_lug, MB_CASE_TITLE, 'UTF-8');
    }
?>
