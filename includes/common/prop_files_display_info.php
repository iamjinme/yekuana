<?php
    // running from system?
    if (empty($CFG) || empty($file)) {
        die;
    }
   
    $values = array(
            __('Propuesta') => $proposal->nombre,
            __('Título') => $file->title,
            __('Descripción') => $file->descr,
            __('Nombre de archivo') => $file->name,
            __('Público') => (empty($file->public)) ? __('No') : __('Si')
        );

    do_table_values($values, 'narrow');
?>
