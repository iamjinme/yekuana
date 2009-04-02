<?php
if (empty($CFG)) {
    die;
}

switch (Context) {
    case 'admin':
        $t = 'R';
        $name = __('Administrador');
        break;

    case 'ponente':
        $t = 'P';
        $name = __('Ponente');
        break;

    case 'asistente':
        $t = 'A';
        $name = __('Asistente');
        break;

    default:
        // force session destroy
        header('Location: ' . get_url('logout'));
}

beginSession($t);

// ignore erros
@session_unset();
@session_destroy();

//start page
do_header();
?>

<h1><?=__('Salida de Sesión') ?> <?=$name ?></h1>
<div class="block"></div>

<p class="center"><?=__('Ha salido exitosamente del sistema.') ?></p>

<?php
do_submit_cancel('', __('Continuar'), get_url());
?>
