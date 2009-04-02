<?php 
// configuration manager
// just switch on/off conf flags
if (empty($CFG) || empty($q) || Context != 'admin' || $USER->id_tadmin != 1) {
    die;
}

$catalogs = array(
    'tadmin' => __('Tipos de administrador'),
    'tasistente' => __('Tipos de asistente'),
    'estado' => __('Estado'),
    'estudios' => __('Estudios'),
    'prop_tipo' => __('Tipos de propuesta'),
    'orientacion' => __('Orientaciones de propuesta'),
    'prop_nivel' => __('Niveles de propuesta'),
    'prop_status' => __('Estados de propuesta')
    );

$catalogs_addremove_field = array('estado', 'orientacion');

?>

<h1><?=__('Administrar Catálogos') ?></h1>

<?php
foreach ($catalogs as $catalog => $desc) {
?>

<h2><?=$desc ?></h2>

<form method="POST" action="">
<?php
    // restet messages
    $errmsg = array();

    $submit = optional_param('submit-'.$catalog);

    // show system config values
    if (!empty($submit)) {
        require($CFG->admdir . 'catalog_optional_params.php');

        // update info if no errors
        if (empty($errmsg)) {
            require($CFG->admdir . 'catalog_update_info.php');
        }
    }

    if (!empty($errmsg)) {
        show_error($errmsg, false);
    }

    include($CFG->admdir . 'catalog_display_input.php');

    do_submit_cancel(__('Guardar'), '', '', 'submit-'.$catalog);
?>

</form>

<?php } ?>

<div class="block"></div>

<?php
do_submit_cancel('', __('Regresar al Menu'), get_url('admin'));
?>
