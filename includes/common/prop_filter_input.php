<?php
// dont show organization events
//$prop_type = get_records_select('prop_tipo', 'id <= 100');
$onChange = 'onChange=\'form.submit()\'';

if (Context == 'admin' && Action != 'listdeletedproposals') {
    $prop_select = ' AND P.id_status != 7';
} elseif (Action == 'listdeletedproposals') {
    $prop_select = ' AND P.id_status = 7';
} else {
    $prop_select = '';
}

$prop_type = get_records_sql('SELECT PT.* FROM '.$CFG->prefix.'prop_tipo PT
                    JOIN '.$CFG->prefix.'propuesta P ON P.id_prop_tipo=PT.id
                    WHERE PT.id <= 100'.$prop_select);

// dont show deleted status
if (Context == 'admin' && Action != 'listdeletedproposals') {
    //dont show programmed
    $status_select = 'S.id < 7';
} elseif (Action == 'listdeletedproposals') {
    $status_select = 'S.id = 7';
} else {
    $status_select = 'S.id != 7';
}

//$status = get_records_select('prop_status', $select);
$status = get_records_sql('SELECT S.* FROM '.$CFG->prefix.'prop_status S
                    JOIN '.$CFG->prefix.'propuesta P ON P.id_status=S.id
                    WHERE '.$status_select.' ORDER BY S.id');

$prop_type_input = do_get_output('do_input_select', array('filter_id_prop_tipo', $prop_type, $id_prop_tipo, true, '', 0, $onChange));

$status_input = do_get_output('do_input_select', array('filter_id_status', $status, $id_status, true, '', 0, $onChange));

$table_data = array();

if (Context == 'admin') {
    $admins = get_records_sql('SELECT ADM.id, ADM.login as descr FROM '.$CFG->prefix.'administrador ADM
                            JOIN '.$CFG->prefix.'propuesta P ON P.id_administrador = ADM.id');

    $none = new StdClass;
    $none->id = -1;

    if (Action == 'listproposals') {
        $none->descr = __('Ninguno');
    } else {
        $none->descr = __('Usuario');
    }

    if (!empty($admins)) {
        // put at the top "none"
        $admins = array_merge(array($none), $admins);
    } else {
        $admins = array($none);
    }

    $admins_input = do_get_output('do_input_select', array('filter_id_adminlogin', $admins, $id_admin, true, '', 0, $onChange));

    if (Action == "listproposals") {
        $table_data[] = array('', __('Tipo:'), __('Estado:'), __('Asignado:'));
        $table_data[] = array(__('Filtro:'), $prop_type_input, $status_input, $admins_input);
    }

    elseif (Action == 'listdeletedproposals') {
        $speakers = get_records_sql('SELECT SP.id, SP.login AS descr FROM '.$CFG->prefix.'ponente SP
                            JOIN '.$CFG->prefix.'propuesta P ON P.id_ponente = SP.id
                            WHERE P.id_status=7');

        $speakers_input = do_get_output('do_input_select', array('filter_id_ponente', $speakers, $id_ponente, true, '', 0, $onChange));

        $table_data[] = array('', __('Modificado por:'), __('Tipo:'), __('Ponente:'));
        $table_data[] = array(__('Filtro:'), $admins_input, $prop_type_input, $speakers_input);
    }

    elseif (Action == 'scheduleevent' || Action == 'addschedule') {
        // acepted proposals
        $prop_type = get_records_sql('SELECT PT.* FROM '.$CFG->prefix.'prop_tipo PT
                                JOIN '.$CFG->prefix.'propuesta P ON P.id_prop_tipo=PT.id
                                WHERE P.id_status=5');
        $prop_type_input = do_get_output('do_input_select', array('filter_id_prop_tipo', $prop_type, $id_prop_tipo, true, '', 0, $onChange));

        $track = get_records_sql('SELECT O.* FROM '.$CFG->prefix.'orientacion O
                            JOIN '.$CFG->prefix.'propuesta P ON P.id_orientacion=O.id
                            WHERE P.id_status=5');
        $track_input = do_get_output('do_input_select', array('filter_id_orientacion', $track, $id_orientacion, true, '', 0, $onChange));

        $table_data[] = array('', __('Tipo:'), __('Orientación:'));
        $table_data[] = array(__('Filtro:'), $prop_type_input, $track_input);
    }
}

else {
    // get all tracks
    //$tracks = get_records('orientacion');
    $tracks = get_records_sql('SELECT O.* FROM '.$CFG->prefix.'orientacion O
                        JOIN '.$CFG->prefix.'propuesta P ON P.id_orientacion=O.id');

    $tracks_input = do_get_output('do_input_select', array('filter_id_orientacion', $tracks, $id_orientacion, true, '', 0, $onChange));

    //headers
    $table_data[] = array('', __('Tipo:'), __('Orientación:'), __('Estado:'));
    $table_data[] = array(__('Filtro:'), $prop_type_input, $tracks_input, $status_input);
}

do_table($table_data, 'prop-filter wide');
?>
