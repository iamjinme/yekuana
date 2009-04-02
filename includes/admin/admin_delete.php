<?php
// dummy check
if (empty($q) || empty($CFG) || $USER->id_tadmin != 1) {
    die;
}

preg_match('#^admin/(\d+)/delete$#', $q, $matches);
$admin_id = (!empty($matches)) ? (int) $matches[1] : 0;

$submit = optional_param('submit');
?>

<h1><?=__('Eliminar Administrador') ?></h1>

<?php
//check owner and status, dont delete acepted, scheduled or deleted¿?
if (!empty($admin_id) && $admin_id != $USER->id && $admin_id != 1)  {

    if (empty($submit)) {
        // confirm delete
?>

<form method="POST" action="";

<?php
        //temporally change USER var
        $USER = get_admin($admin_id);

        include($CFG->comdir . 'user_display_info.php');
        do_submit_cancel(__('Eliminar'), __('Cancelar'), $return_url);
?>

</form>

<?php
    } else {
        // delete!
        // (really change status to deleted)

        // this never should be happen
        if ($admin_id == 1) {
            die;
        }

        // first try update reference
        $prop_update = 'UPDATE propuesta SET id_administrador=1 WHERE id_administrador='.$admin_id;
        $event_update = 'UPDATE evento SET id_administrador=1 WHERE id_administrador='.$admin_id;

        if (!execute_sql($prop_update, false) || !execute_sql($event_update, false)) {
            show_error(__('Ocurrió un error al intentar eliminar el registro.'));
            $rs = null;
        } else {
            $rs = delete_records('administrador', 'id', $admin_id);
        }

        if (!$rs) {
            show_error(__('Ocurrio un error al eleminar el registro.'));
        } else {
?> 

<div class="block"></div>

<p class="center"><?=__('El administrador fue eliminado exitosamente. Las propuestas que ha autorizado han sido asiganadas al administrador principal.') ?></p>

<?php 
        }

        do_submit_cancel('', __('Continuar'), $return_url);
    }

} else {
?>

<h1><?=__('Administrador no encontrada') ?></h1>

<div class="block"></div>
<p class="center"><?=__('El usuario no existe.') ?></p>

<?php
    do_submit_cancel('', __('Regresar'), $return_url);
}
?>



