<?php
// dummy check
if (empty($q) || empty($CFG)) {
    die;
}

if (Context == 'ponente') {
    preg_match('#^speaker/proposals/(\d+)/files/delete/(\d+)/?.*$#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;
    $file_id = (!empty($matches)) ? (int) $matches[2] : 0;

    //Check proposal owner
    $proposal = get_proposal($proposal_id, $USER->id);

    if (!empty($proposal)) {
        //get file
        $file = get_record('prop_files', 'id', $file_id, 'id_propuesta', $proposal->id);
        $return_url = get_url('speaker/proposals/' . $proposal->id . '/files');
    } else {
        $return_url = get_url('speaker/proposals');
    }

}

$submit = optional_param('submit');
//can't delete files from accepted/canceled/deleted/programmed proposals
if (!empty($proposal) && !empty($file) && $proposal->id_status < 5)  {
?>

<h1><?=__('Eliminar archivo') ?></h1>

<?php

    if (empty($submit)) {
        // confirm delete
?>

<form method="POST" action="";

<?php
        include($CFG->comdir . 'prop_files_display_info.php');
        do_submit_cancel(__('Eliminar'), __('Cancelar'), $return_url);
?>

</form>

<?php
    } else {
        // delete!

        //FIXME: error reporting
        @unlink($CFG->files . 'proposals/' . $proposal->id . '/'. $file->name);
        delete_records('prop_files', 'id', $file->id, 'id_propuesta', $proposal->id);
?> 

<div class="block"></div>

<p class="center"><?=__('El archivo fue eliminado exitosamente.') ?></p>

<?php 
        do_submit_cancel('', __('Continuar'), $return_url);
    }

} else {
?>

<h1><?=__('Propuesta y/o archivo no encontrado') ?></h1>

<div class="block"></div>
<p class="center"><?=__('Registros de propuesta no encontrados. Posiblemente no existan o no tengas acceso para eliminar los archivos de la propuesta.') ?></p>

<?php
    do_submit_cancel('', __('Regresar'), $return_url);
}
?>
