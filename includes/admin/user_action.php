<?php

if (empty($CFG) || empty($q) || Context != 'admin') {
    die;
}

preg_match('#^admin/persons/control/(\d+)#', $q, $matches);
$user_id = (int) $matches[1];

// update reg flag status
if (!empty($user_id)) {

    if (Action == 'controlpersons') {
        $asistencia = get_field('asistente', 'asistencia', 'id', $user_id);

        $user = new StdClass;
        $user->id = $user_id;
        //toggle asistencia
        $user->asistencia = (empty($asistencia)) ? 1 : 0;
    }

    if ($rs = update_record('asistente', $user)) {
        $errmsg[] = __('Se ha actualizado el tipo de administrador para el usuario');
    } else {
        $errmsg[] = __('Ocurrió un error al actualizar cambiar el estado del registro');
    }
}
//get return_path
$return_path = (empty($_SESSION['return_path'])) ? '' : $_SESSION['return_path'];

//clear return_path
$_SESSION['return_path'] = '';

header('Location: ' . $return_path);
