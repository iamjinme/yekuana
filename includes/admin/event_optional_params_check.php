<?php
    // halt if running directly
    if (empty($CFG)) {
        die;
    }

    // check submit value
//    if ((empty($event->id_fecha) && Action != 'newevent' && $proposal->id_prop_tipo < 100)
//        || (empty($id_lugar) && Action != 'newevent' && $proposal->id_prop_tipo < 100)
    if ((empty($event->id_fecha) && Action != 'newevent')
        || (empty($event->id_lugar) && Action != 'newevent')
        || empty($event->hora)
        || Action == 'newevent'
        && (empty($id_prop_tipo)
            || empty($duracion)
            || empty($id_orientacion)
            || empty($resumen)
            || empty($name))) {

            $errmsg[] = __("Verifica que los datos obligatorios los hayas introducido correctamente.");
    }

    if (empty($errmsg) && Action == 'newevent') {
        // build event-proposal
        $proposal->id_ponente = 1; //our events admin speaker
        $proposal->id_status = 5; //acepted status
        $proposal->id_administrador = $USER->id; //current admin
        $proposal->reg_time = strftime('%Y%m%d%H%M%S'); //reg_time

        // build event
        $event->id_administrador = $USER->id;
        $event->reg_time = strftime('%Y%m%d%H%M%S');
    } else {
        // make sure not to update reg_time
        unset($event->reg_time);
    }

    // check for event slots
    if (empty($errmsg)) {
        $hora_ini = $event->hora;
        $hora_fin = $event->hora + $proposal->duracion;

        if ($hora_fin > $CFG->def_hora_fin) {
            $errmsg[] = __('La duración de esta ponencia supera la hora final del evento.');
        }

        // safe value for test
        $event_id = (empty($event->id)) ? 0 : $event->id;

        // DISABLED
        if (false && $proposal->id_prop_tipo >= 100 && (!empty($extraordinario) || empty($event->id_fecha) || empty($id_lugar))) {
            // magistral conference or organizational event
            // with special settings: extraordinary, all dates, all rooms
            //
            if (!empty($extraordinario) && !empty($event->id_fecha) && !empty($event->id_lugar)) {
                // main event, reserves needs all rooms at it's hour
                // look for events ocurrences in all rooms at same date and hour
                $query = 'SELECT EO.id_evento FROM '.$CFG->prefix.'evento_ocupa EO
                        LEFT JOIN '.$CFG->prefix.'lugar L ON L.id = EO.id_lugar
                        WHERE EO.hora = ? AND EO.id_fecha ='.$event->id_fecha.' AND EO.id_evento<>'.$event_id;

                // check for availability
                for ($hhora=$hora_ini; $hhora < $hora_fin; $hhora++){

                    $testevent_slot = get_records_sql($query, array($hhora));

                    if (!empty($testevent_slot)) {
                        // at least there is one
                        $testevent = array_pop($testevent_slot);

                        if (Action == 'newevent' || Action == 'scheduleevent' || $event->id != $testevent->id_evento) {

                            // get conflict proposals details
                            $query = 'SELECT P.id, P.nombre FROM '.$CFG->prefix.'propuesta P
                                JOIN '.$CFG->prefix.'evento E ON E.id='.$testevent->id_evento.'
                                WHERE P.id=E.id_propuesta GROUP BY E.id';

                            $conflict_proposal = get_record_sql($query);

                            $url = get_url('admin/proposals/'.$conflict_proposal->id);
                            $event_link = "<a href=\"{$url}\" title=\"" . __("Evento en Conflicto") . "\">{$conflict_proposal->nombre}</a>";

                            $errmsg[] = __('No se puede reservar los demás lugares. Existe un conflicto en la hora y fecha escogida: ') . $event_link;
                            break;
                        }
                    }
                }
            } 

            else  {
                die('duh!');
            }
            
        } else {
            // normal event 

            //search for ocurrence
            for ($hhora=$hora_ini; $hhora < $hora_fin; $hhora++) {

                $testevent_place = get_record('evento_ocupa', 'id_fecha', $event->id_fecha, 'id_lugar', $event->id_lugar, 'hora', $hhora);

                if (!empty($testevent_place)) {

                    if (Action == 'newevent' || Action == 'scheduleevent' || $event->id != $testevent_place->id_evento) {

                        // get conflict proposals details
                        $query = 'SELECT P.id, P.nombre FROM '.$CFG->prefix.'propuesta P
                            JOIN '.$CFG->prefix.'evento E ON E.id='.$testevent_place->id_evento.'
                            WHERE P.id=E.id_propuesta GROUP BY E.id';

                        $conflict_proposal = get_record_sql($query);

                        $url = get_url('admin/proposals/'.$conflict_proposal->id);
                        $event_link = "<a href=\"{$url}\" title=\"" . __("Evento en Conflicto") . "\">{$conflict_proposal->nombre}</a>";

                        $errmsg[] = __('La fecha, hora y lugar que elegiste tiene conflictos con otro evento ya programado: ') . $event_link;
                        break;
                    }
                }
            }

        }
    }

?>
