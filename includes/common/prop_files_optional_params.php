<?php

    $submit = optional_param('submit');

    // input variables
    $title = optional_param('S_title');
    $descr = optional_param('S_descr');
    $public = optional_param('I_public', 0, PARAM_INT);

    //clean filename, fix issues on uploading filename with accents
    if (!empty($_FILES['S_filename']['name'])) {
        $_FILES['S_filename']['name'] = clean_filename($_FILES['S_filename']['name']);
    }

    $filename = (empty($_FILES['S_filename']['name'])) ? '' : $_FILES['S_filename']['name'];

    if (empty($title) || empty($descr) || (empty($filename) && Action != 'editfile')) {
        $errmsg[] = __('Verifica que los datos obligatorios los hayas introducido correctamente');
    }

    if (Action != 'editfile') {
        //check if name exists
        if (empty($errmsg)) {
            if (record_exists('prop_files', 'name', $filename)) {
                $errmsg[] = __('El nombre del archivo ya existe.');
            }
        }

        //initialize $file for display
        $file = new StdClass;
        $file->title = $title;
        $file->descr = $descr;
        $file->public = $public;

    }
?>
