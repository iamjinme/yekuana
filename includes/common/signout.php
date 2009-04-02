<?php

    if (empty($type)) {
        die;
    }

    require_once(dirname(__FILE__). '/../lib.php');

    // check current session
    beginSession($type);

    // end session
    session_unset();
    session_destroy();

    // show page
    do_header();

    if ($type == 'R') {
        $who = __('Administrador');
    } elseif ($type == 'P') {
        $who = __('Ponente');
    } elseif ($type == 'A') {
        $who = __('Asistente');
    }
?>

<h1><?=__('Salida de Sesión') ?> <?=$who ?></h1>

<div class="block"></div>

<p class="center"><?=__('Ha salido exitosamente del sistema.') ?></p>

<?php
    do_submit_cancel('', __('Regresar'), get_url());
    do_footer();
?>
