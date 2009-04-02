<?php
    // running directly?
    if (empty($CFG) || Context != 'admin') {
        die;
    }

    // Common values
    $submit = optional_param('submit');

    if (Action == 'newevent' || $proposal->id_prop_tipo >= 100) {
        $extraordinario = optional_param('I_extraordinario');
    }

    if (Action == 'newevent') {
        $id_prop_tipo = optional_param('I_id_prop_tipo', 0, PARAM_INT);
        $duracion = optional_param('I_duracion', 0, PARAM_INT);
        $resumen = optional_param('S_resumen');
        $name = optional_param('S_nombre');
        $id_orientacion = optional_param('I_id_orientacion');

        //setup proposal
        // FIXME: allow repeated names? 
        $proposal->nombre = $name;
        $proposal->id_prop_tipo = $id_prop_tipo;
        $proposal->duracion = $duracion;
        $proposal->resumen = $resumen;
        $proposal->id_orientacion = $id_orientacion;
    }

    $id_fecha = optional_param('I_id_fecha', 0, PARAM_INT);
    $id_lugar = optional_param('I_id_lugar', 0, PARAM_INT);
    $hora = optional_param('I_hora', 0, PARAM_INT);

    if (!empty($submit) || Action == 'scheduleevent' || Action == 'newevent') {
        $event->id_fecha = $id_fecha;
        $event->id_lugar = $id_lugar;
        $event->hora = $hora;
    }
?>
