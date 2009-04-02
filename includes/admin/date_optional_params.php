<?php
    // running directly?
    if (empty($CFG) || Context != 'admin') {
        die;
    }

    // Common values
    $submit = optional_param('submit');

    $e_day = optional_param('I_e_day', 0, PARAM_INT);
    $e_month = optional_param('I_e_month', 0, PARAM_INT);
    $e_year = optional_param('I_e_year', 0, PARAM_INT);
    $descr = optional_param('S_descr');

    if (!empty($submit) || Action == 'newdate') {
        
        $date->fecha = sprintf('%04d-%02d-%02d', $e_year, $e_month, $e_day);
        $date->descr = $descr;
    }
?>
