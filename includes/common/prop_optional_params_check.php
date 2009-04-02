<?php
    // halt if running directly
    if (empty($CFG)) {
        die;
    }

    // check submit value
    if (!empty($submit)
        && (Context == 'admin' || Context == 'ponente')) {
        // Verificar si todos los campos obligatorios no estan vacios
        if ((Context == 'admin' && empty($login))
            || empty($proposal->nombre)
            || empty($proposal->id_orientacion)
            || empty($proposal->id_nivel)
            || empty($proposal->id_prop_tipo)
            || empty($proposal->duracion)
            || empty($proposal->resumen)) { 

            $errmsg[] = __("Verifica que los datos obligatorios los hayas introducido correctamente.");
        }

        if (Context == 'admin') {
            $speaker_id = get_field('ponente', 'id', 'login', $login);

            if (empty($speaker_id)) {
                $errmsg[] = __('El ponente que elegiste no existe. Por favor elige otro.');
            } 
        } else {
            $speaker_id = $USER->id;
        }

        // set proposal owner
        $proposal->id_ponente = $speaker_id;

        if ($proposal->duracion > 2 && $proposal->id_prop_tipo < 50) {
            $errmsg[] = __('Sólo talleres o tutoriales pueden tener durar más de 2 horas');
        }

        if (empty($errmsg)) {
//            $record = get_record('propuesta', 'nombre', $nombreponencia, 'id_ponente', $idponente);
//            $record = get_record('propuesta', 'nombre', $nombreponencia);
            $record = get_proposals("P.nombre='{$proposal->nombre}'", 1);

            if (!empty($record)) {

                if (Action == 'newproposal'
                    || (Action == 'updateproposal' && ($record->id != $proposal->id || $record->id_ponente != $proposal->id_ponente))) {

                    $errmsg[] = __('El nombre de la ponencia ya ha sido dado de alta.');

                } else {
                    // record not empty and submit == update and user is admin or 
                    // user is owner
                    // set id for proposals 
                    //$idponencia = $record->id; 
                }
            }
        }

    }
?>
