<?php
// dummy check
if (empty($q) || empty($CFG)) {
    die;
}

if (Context == 'ponente') {
    preg_match('#^speaker/proposals/(\d+)/delete$#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;

    //Check proposal owner
    $proposal = get_proposal($proposal_id, $USER->id);
}

elseif (Context == 'admin') {
    preg_match('#^admin/proposals/(\d+)/delete#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;

    $proposal = get_proposal($proposal_id);
}

$submit = optional_param('submit');

//check owner and status, dont delete acepted, scheduled or deleted¿?
// can delete canceled proposal
if (!empty($proposal) && ($proposal->id_status < 5 || $proposal->id_status = 6))  {
?>

<h1><?=__('Eliminar propuesta') ?></h1>

<?php

    if (empty($submit)) {
        // confirm delete
?>

<form method="POST" action="";

<?php
        // flag to not show resume and extra info
        $prop_noshow_resume = true;
        
        include($CFG->comdir . 'prop_display_info.php');
        do_submit_cancel(__('Eliminar'), __('Cancelar'), $return_url);
?>

</form>

<?php
    } else {
        // delete!
        // (really change status to deleted)

        // only update these values
        $prop = new StdClass;
        $prop->id = $proposal->id;
        $prop->id_status = 7;

        if (!$rs = update_record('propuesta', $prop)) {
            show_error(__('Ocurrio un error al eleminar el registro.'));
        } else {
?> 

<div class="block"></div>

<p class="center"><?=__('La propuesta fue eliminada exitosamente.') ?></p>

<?php 
        }

        do_submit_cancel('', __('Continuar'), $return_url);
    }

} else {
?>

<h1><?=__('Propuesta no encontrada') ?></h1>

<div class="block"></div>
<p class="center"><?=__('Registros de propuesta no encontrados. Posiblemente no existan o no tengas acceso para eliminar la propuesta.') ?></p>

<?php
    do_submit_cancel('', __('Regresar'), $return_url);
}
?>
