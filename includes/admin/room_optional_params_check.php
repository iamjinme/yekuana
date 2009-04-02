<?php
    // halt if running directly
    if (empty($CFG)) {
        die;
    }

    // check submit value
    if (empty($room->nombre_lug) || empty($room->ubicacion)) {
            $errmsg[] = __("Verifica que los datos obligatorios los hayas introducido correctamente.");
    }

    if (empty($errmsg)) {
        $testroom = get_record('lugar', 'nombre_lug', $room->nombre_lug);

        if (!empty($testroom)) {
            if (Action == 'newroom' || $room->id != $testroom->id) {
                $errmsg[] = __('El nombre del lugar ya ha sido dado de alta.');
            }
        }
    }

?>
