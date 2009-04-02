<?php
    // running from system?
    if (empty($CFG) || empty($date)) {
        die;
    }

    // initalize var
    $values = array(
        __('Fecha de evento: ') => friendly_date($date->fecha, true),
        __('Descripción: ') => $date->descr
        );

    do_table_values($values, 'narrow');
?>
