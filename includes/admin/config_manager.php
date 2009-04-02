<?php 
// configuration manager
// just switch on/off conf flags
if (empty($CFG) || empty($q) || Context != 'admin' || $USER->id_tadmin != 1) {
    die;
}

require($CFG->admdir . 'config_optional_params.php');
?>

<h1><?=__('Configuración de Yupana') ?></h1>

<?php
// show register open/close
require($CFG->admdir . 'config_reg_flags.php');

// show system config values
if (!empty($submit)) {
    require($CFG->admdir . 'config_optional_params_check.php');

    // update info if no errors
    if (empty($errmsg)) {
        require($CFG->admdir . 'config_update_info.php');
    }
}
?>

<h1><?=__('Valores del Sistema') ?></h1>

<?php
if (!empty($errmsg)) {
    show_error($errmsg, false);
}
?>

<form method="POST" action="">

    <h3><?=__('Información General') ?></h3>

<?php
require($CFG->admdir . 'config_input_general.php');
do_submit_cancel(__('Guardar'), '');
?>

    <h3><?=__('Configuración Adicional') ?></h3>

<?php
require($CFG->admdir . 'config_input_system.php');
do_submit_cancel(__('Guardar'), '');
?>

</form>

<div class="block"></div>

<?php
do_submit_cancel('', __('Regresar al Menu'), $return_url);
?>
