<?php
    // running from system?
    if (empty($CFG) || empty($room)) {
        die;
    }
   
    // initalize var
    $values = array();

    $cupo = (empty($room->cupo)) ? __('Salón para conferencias') : sprintf(__('%s personas'), $room->cupo);

    $values = array(
        __('Nombre') => $room->nombre_lug,
        __('Ubicación') => $room->ubicacion,
        __('Capacidad') => $cupo
        );

    do_table_values($values, 'narrow');
?>
