<?php

if (empty($CFG) || empty($q) || Context != 'admin') {
    die;
}

if (preg_match('#^admin/proposals/deleted/\d+/status/\d+/?#', $q)) {
    preg_match('#^admin/proposals/deleted/(\d+)/status/(\d+)#', $q, $matches);
    $prop_id = (int) $matches[1];
    $prop_id_status = (int) $matches[2];

    $return_url = get_url('admin/proposals/deleted');
} 

elseif (preg_match('#^admin/proposals/\d+/status/\d+/?#', $q)){
    preg_match('#^admin/proposals/(\d+)/status/(\d+)#', $q, $matches);
    $prop_id = (int) $matches[1];
    $prop_id_status = (int) $matches[2];

    $return_url = get_url('admin/proposals');
}

// update reg flag status
if (!empty($prop_id) && !empty($prop_id_status) && $prop_id_status < 7) {

    $prop = new StdClass;
    $prop->id = $prop_id;
    $prop->id_status = $prop_id_status;
    $prop->id_administrador = $USER->id;

    if ($rs = update_record('propuesta', $prop)) {
        $errmsg[] = __('Se ha actualizado el estado de la ponencia');
    } else {
        $errmsg[] = __('Ocurrió un error al cambiar el estado del registro');
    }
}

header('Location: ' . $return_url);
