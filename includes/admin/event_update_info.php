<?php
    if (empty($CFG) || Context != 'admin') {
        die; //exit
    }

    if (Action == 'newevent') {
        // insert our event-proposal
        $rs = insert_record('propuesta', $proposal);

        if (!$rs) {
            show_error(__('No se pudo insertar los datos. Por favor contacte a su administrador.'), false);
            die;
        }

        // refresh proposal id
        $proposal->id = (int) $rs;
        // set prop id
        $event->id_propuesta = $proposal->id;

    }

    // new event schedule
    if (Action == 'scheduleevent' || Action == 'newevent' || Action == 'addschedule_action') {
        $rs = insert_record('evento', $event);

        if (!$rs) {
            // Fatal error
            show_error(__('No se pudo insertar los datos.'), false);
            do_submit_cancel('', __('Regresar'), get_url('admin/events'));
            die; //exit
        }

        //refresh event id
        $event->id = (int)$rs;

        if (empty($event->id)) {
            show_error(__('No se pudo insertar los datos. Por favor contacte su administrador.'), false);
            die;
        }

        // continue the insert
        //DISABLE
        if (false && $proposal->id_prop_tipo >= 100) {
            // organizational event, can use all dates or all rooms

            // if extraordinario,  {
            // }



        } else {
            // normal event        
            $hora_fin = $event->hora + $proposal->duracion;

            for ($hhora=$event->hora; $hhora < $hora_fin; $hhora++) {

                $query = 'INSERT INTO '.$CFG->prefix.'evento_ocupa
                    (id_evento,hora,id_fecha,id_lugar)
                     VALUES ('.$event->id.',
                         '.$hhora.',
                         '.$event->id_fecha.',
                         '.$event->id_lugar.')';

                $rs = execute_sql($query, false);

                if (!$rs) {
	            show_error(__('No se pudo insertar los datos.'), false);
                    //FIXME: ignore errors
                }
            }

        }
        // update proposal status
        $prop = new StdClass;
        $prop->id = $proposal->id;
        $prop->id_status = 8;

        $rs = update_record('propuesta', $prop);

    } elseif (!empty($event->id)) {
        // update records
        $rs = update_record('evento', $event);

        if (!$rs) {
            // Fatal error
            show_error(__('No se pudo actualizar los datos.'));
            do_submit_cancel('', __('Regresar'), get_url('admin/events'));
            die; //exit
        }


        // disabled
        if (FALSE && $proposal->id_prop_tipo >= 100 && (!empty($extraordinario) || empty($event->id_fecha) || empty($event->id_lugar))) {
            // delete all current event slots
            delete_records('evento_ocupa', 'id_evento', (int) $event->id);

            if (!empty($extraordinario) && !empty($event->id_fecha) && !empty($event->id_lugar)) {
                $hora_fin = $event->hora + $proposal->duracion;

                // reserves all time slot for each room
                $rooms = get_records('lugar');

                foreach ($rooms as $room) {
                    for ($hhora = $event->hora; $hhora < $hora_fin; $hhora++) {
                        $query = 'INSERT INTO '.$CFG->prefix.'evento_ocupa(id_evento,hora,id_fecha,id_lugar) VALUES(%d,%d,%d,%d)';

                        // set event for proposal at room
                        if ($room->id == $event->id_lugar) {
                            $event_id = $event->id;
                        } else {
                            $event_id = 1; // reserved proposal
                        }

                        // build query
                        $query = sprintf($query, $event_id, $hhora, $event->id_fecha, $room->id);

                        $rs = execute_sql($query, false);

                        if (!$rs) {
                            //FIXME: errors
                        }
                    }
                }

            }

        } else {
            // normal update
            // delete current references
                delete_records('evento_ocupa', 'id_evento', $event->id);

            // insert new slots
            $hora_fin = $event->hora + $proposal->duracion;

            for ($hhora=$event->hora; $hhora < $hora_fin; $hhora++) {
                $query = 'INSERT INTO '.$CFG->prefix.'evento_ocupa
                    (id_evento,hora,id_fecha,id_lugar)
                     VALUES ('.$event->id.',
                         '.$hhora.',
                         '.$event->id_fecha.',
                         '.$event->id_lugar.')';
                
                $rs = execute_sql($query, false);

                if (!$rs) {
                    //FIXME: ignore errors
                }
            }

            // no need to update proposal
        }

    }


?>
