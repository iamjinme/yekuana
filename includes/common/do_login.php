<?php
if (empty($CFG)) {
    die;
}

if (!defined('Context')
    || (Context != 'admin'
        && Context != 'ponente'
        && Context != 'asistente')) {

    header('Location: ' . get_url());
}

// messages holder
$errmsg = array();

require($CFG->comdir . 'signin.php');

$title = __('Inicio de Sesión');

// now we can start output content
do_header($title);

if (Context == 'admin') { ?>

<h1><?=__('Panel de administración') ?></h1>
<h2 class="center"><?=$title ?></h2> 

<?php } elseif (Context == 'ponente') { ?>

<h1><?=__('Inicio de Sesión Ponente') ?></h1>

<?php } elseif (Context == 'asistente') { ?>

<h1><?=__('Inicio de Sesión Asistente') ?></h1>

<?php
}

if (!empty($errmsg)) {
    show_error($errmsg);
} elseif ($exp == 'exp') {
    show_error(__('Su sesión ha caducado o no incio correctamente. Por favor trate de nuevo'));
}

require($CFG->comdir . 'display_login_form.php');
?>
