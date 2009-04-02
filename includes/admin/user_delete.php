<?php
// dummy check
if (empty($q) || empty($CFG) || $USER->id_tadmin != 1) {
    die;
}

if (Action == 'deleteperson') {
    preg_match('#^admin/persons/(\d+)/delete$#', $q, $matches);
    $user_id = (!empty($matches)) ? (int) $matches[1] : 0;
    $desc = __('Asistente');
    $dbtable = 'asistente';
    $user = get_person($user_id);
    $local_url = '/persons';
} 

elseif (Action == 'deletespeaker') {
    preg_match('#^admin/speakers/(\d+)/delete$#', $q, $matches);
    $user_id = (!empty($matches)) ? (int) $matches[1] : 0;
    $desc = __('Ponente');
    $dbtable = 'ponente';
    // protect event admin speaker
    if ($user_id == 1) {
        $optional_message = __('No puedes eliminar este usuario, esta reservado como administrador de eventos.');
    } else {
        $user = get_speaker($user_id);
    }
    $local_url = '/speakers';
}

elseif (Action == 'deleteadmin') {
    preg_match('#^admin/(\d+)/delete$#', $q, $matches);
    $user_id = (!empty($matches)) ? (int) $matches[1] : 0;
    $desc = __('Administrador');
    $dbtable = 'administrador';
    if ($user_id == 1) {
        $optional_message = __('No puedes eliminar al administrador principal.');
    } else {
        $user = get_admin($user_id);
    }
    $local_url = '/list';
}

$submit = optional_param('submit');
?>

<h1>Eliminar <?=$desc ?></h1>

<?php
// check if user want to delete himself or main admin
if (!empty($user) && ($user_id != $USER->id || Action == 'deleteperson'))  {

    if (empty($submit)) {
        // confirm delete
?>

<form method="POST" action="">

<?php
        if (!empty($_SESSION['return_path'])) {
            $local_url = $_SESSION['return_path'];
        }

        include($CFG->comdir . 'user_display_info.php');

        do_submit_cancel(__('Eliminar'), __('Cancelar'), get_url('admin'.$local_url));
?>

</form>

<?php
        if (Action == 'deletespeaker') {
            $msg = __('Propuestas Enviadas');
        }
?>

<div class="block"></div>

<h2 class="center"><?=$msg ?></h2>

<?php
        include($CFG->comdir . 'prop_list.php');

    } else {
        // delete!

        // this never should be happen
        if (Action == 'deleteadmin' && $user_id == 1) {
            die;
        }

        $fail = null;

        if (Action == 'deleteadmin') {
            $return_url = get_url('admin/list');

            // first try update reference
            $prop_update = 'UPDATE propuesta SET id_administrador=1 WHERE id_administrador='.$user->id;
            $event_update = 'UPDATE evento SET id_administrador=1 WHERE id_administrador='.$user->id;

            if (!execute_sql($prop_update, false) || !execute_sql($event_update, false)) {
                show_error('Ocurrio un error al intentar eliminar el registro.');
                $rs = null;
            } else {
                $rs = delete_records('administrador', 'id', $user->id);
            }

            $desc_more = 'Las propuestas que ha aprobado han sido reasiganadas al administrador principal.';
        }

        elseif (Action == 'deletespeaker') {
            $return_url = get_url('admin/speakers');

            // get user proposals
            $props = get_records('propuesta', 'id_ponente', $user->id);

            if (!empty($props)) {
                // delete events references
                foreach ($props as $prop) {
                    $event_id = get_field('evento', 'id', 'id_propuesta', $prop->id);
                    // delete event_place
                    delete_records('evento_ocupa', 'id_evento', $event_id);
                    // delete subscriptions
                    delete_records('inscribe', 'id_evento', $event_id);
                    // finally delete event
                    delete_records('evento', 'id', $event_id);
                    // delete prop
                    delete_records('propuesta', 'id_ponente', $user->id);
                }
            }

            // and... delete user
            $rs = delete_records('ponente', 'id', $user->id);

            $desc_more = __('Las ponencias que el usuario ha enviado han sido eliminadas, los eventos relacionados y los inscritos a sus talleres.');
        }

        elseif (Action == 'deleteperson') {
            $return_url = get_url('admin/persons');

            if (!empty($_SESSION['return_path'])) {
                $return_url = $_SESSION['return_path'];
                //clear return_path
                $_SESSION['return_url'] = '';
            }
            // delete user
            $rs = delete_records('asistente', 'id', $user->id);

            // delete user subscriptions
            delete_records('inscribe', 'id_asistente', $user->id);

            $desc_more = __('Los espacios que ocupaba en los talleres han sido liberados.');
        }


        if (!$rs) {
            show_error(__('Ocurrio un error al eleminar el registro.'), false);
        } else {
?> 

<div class="block"></div>

<p class="center"><?=$desc ?> <?=__('fue eliminado exitosamente.') ?></p>
<p class="center"><?=$desc_more ?></p>

<?php 
        }

        do_submit_cancel('', __('Continuar'), $return_url);
    }

} else {
?>

<div class="block"></div>
<p class="center"><?=__('El usuario no existe.') ?></p>
<p class="center"><?=$optional_message ?></p>

<?php
    do_submit_cancel('', __('Regresar'), $return_url);
}
?>
