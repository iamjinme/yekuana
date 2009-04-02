<?php
    // halt if running directly
    if (empty($CFG)) {
        die;
    }

    // check submit value
    if (empty($e_year) || empty($e_month) || empty($e_day)) {
            $errmsg[] = __("Verifica que los datos obligatorios los hayas introducido correctamente.");
    }

    if (empty($errmsg)) {
        $testdate = get_record('fecha_evento', 'fecha', $date->fecha);

        if (!empty($testdate)) {
            if (Action == 'newdate' || $date->id != $testdate->id) {
                $errmsg[] = __('La fecha que elegiste ya ha sido dado de alta.');
            }
        }
    }

?>
