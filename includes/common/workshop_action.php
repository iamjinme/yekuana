<?php

if (empty($CFG) || empty($q)) {
    die;
}

if (Context == 'asistente') {
    preg_match('#^person/workshops/(\d+)/(.+)#', $q, $matches);
    $workshop_id = (int) $matches[1];
    $action = $matches[2];
    $userid = $USER->id;

    $workshop = get_proposal($workshop_id);

    $return = get_url('person/workshops');

    $limit = count_records('inscribe', 'id_asistente', $userid);
}

else {
    $return = get_url();
}

// check if wid is a workshop or limit of subscribed workshop
// TODO: move to config limit of workshops
if ($workshop->id_prop_tipo < 50 || $workshop->id_prop_tipo >= 100 || ($action == 'subscribe' && $limit > $CFG->max_inscripcionTA)) {
    // clear workshop
    unset($workshop);
}

// update reg flag status
if (!empty($workshop) && !empty($workshop->id_evento) && !empty($action)) {

    if ($action == 'subscribe') {
        $query = 'INSERT INTO '.$CFG->prefix.'inscribe(id_asistente,id_evento,reg_time) VALUES(%d,%d,%s)';

        $time = strftime('%Y%m%d%H%M%S');
        // build query
        $query = sprintf($query, $userid, $workshop->id_evento, $time);

        $rs = execute_sql($query, false);
    }

    elseif ($action == 'unsubscribe') {
        $rs = delete_records('inscribe', 'id_evento', $workshop->id_evento, 'id_asistente', $userid);
    }

    if (!$rs) {
        $errmsg[] = __('Ocurrió un error al actualizar los datos.');
    }
}

header('Location: ' . $return);
