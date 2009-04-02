<?php
// dummy check
if (empty($q) || empty($CFG)) {
    die;
}

if (Context == 'ponente') {
    preg_match('#^speaker/proposals/(\d+)/files/?#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;

    //Check proposal owner
    $proposal = get_proposal($proposal_id, $USER->id);
}

elseif (Context == 'admin') {
    preg_match('#^admin/proposals/(\d+)/files#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;

    $proposal = get_proposal($proposal_id);
}


require($CFG->comdir . 'prop_files_optional_params.php');

//check owner and status, dont delete acepted, scheduled or deleted¿?
// can delete canceled proposal
if (!empty($proposal) && ($proposal->id_status < 5 || $proposal->id_status = 6))  {

    if (!empty($submit)) {

        if (empty($errmsg)) {
            //upload manager
            require_once($CFG->incdir . 'uploadlib.php');

            $um = new upload_manager('S_filename', false, true);

            $uploaddir = 'proposals/' .$proposal->id;

            if ($um->process_file_uploads($uploaddir)) {
                $f = new StdClass;

                $f->id_propuesta = $proposal->id;
                $f->name = $filename;
                $f->title = $title;
                $f->descr = $descr;
                $f->public = $public;
                $f->size = $um->get_filesize();
                $f->reg_time = time();

                //insert into db
                if ($rs = insert_record('prop_files', $f)) {
                    $errmsg[] = __('Archivo registrado exitosamente.');

                    //reset file
                    $file = new StdClass;
                    $file->title = '';
                    $file->descr = '';
                    $file->public = 0;

                } else {
                    $errmsg[] = __('Ocurrió un error al registrar el archivo.');
                }
            }

            else {
                $errmsg[] = __('Error al subir el archivo.');
                $errmsg[] = $um->get_errors();
            }

            //show messages
            show_error($errmsg, false);

        } else {
            show_error($errmsg);
        }
    }
?>

<h1><?=__('Archivos adjuntos de la propuesta') ?></h1>

<?php
    $prop_noshow_resume = true;
        
    include($CFG->comdir . 'prop_display_info.php');
?>

<h3 class="center"><?=__('Lista de archivos') ?></h3>

<?php include($CFG->comdir . 'prop_files_display.php'); ?>

<h3 class="center"><?=__('Subir Archivo') ?></h3>

<form method="POST" action="" enctype="multipart/form-data">

<?php
    include($CFG->comdir . 'prop_files_input.php'); 

    do_submit_cancel(__('Subir'), __('Regresar'), $return_url);

} else {
    
?>


<div class="block"></div>
<p class="center"><?=__('Registros de propuesta no encontrados. Posiblemente no existan o no tengas acceso para eliminar la propuesta.') ?></p>

<?php
    do_submit_cancel('', __('Regresar'), $return_url);
}
?>
