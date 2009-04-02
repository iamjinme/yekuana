<?php

if (empty($CFG) || empty($q) || Context != 'admin') {
    die;
}

preg_match('#^admin/(\d+)/type/(\d+)#', $q, $matches);
$admin_id = (int) $matches[1];
$admin_tadmin = (int) $matches[2];

// update reg flag status
if (!empty($admin_id) && !empty($admin_tadmin) && $admin_id != 1 && $admin_id != $USER->id && ($admin_tadmin >0 && $admin_tadmin <4)) {

    $admin = new StdClass;
    $admin->id = $admin_id;
    $admin->id_tadmin = $admin_tadmin;

    if ($rs = update_record('administrador', $admin)) {
        $errmsg[] = __('Se ha actualizado el tipo de administrador para el usuario');
    } else {
        $errmsg[] = __('Ocurrió un error al actualizar el tipo de administrador del usuario');
    }
}

header('Location: ' . get_url('admin/list'));
