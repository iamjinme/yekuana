<?php
// dummy check
if (empty($CFG) || Context != 'admin') {
    die;
}

$dates = get_records('fecha_evento', '', '', 'fecha');
?>

<h1><?=__('Listado de fechas para eventos') ?></h1>

<?php
if (!empty($dates)) {
?>

<h4><?=__('Fechas registradas') ?>: <?=sizeof($dates) ?></h4>

<?php
    // build data table
    $table_data = array();
    $table_data[] = array(__('Fecha'), __('Descripción'), '', '');

    foreach ($dates as $date) {

        $url = get_url('admin/dates/'.$date->id);
        $fdate = friendly_date($date->fecha, true);
        $l_nombre = <<< END
<ul><li>
<a class="speaker" href="{$url}">{$fdate}</a>
</li></ul>
END;

        $url = get_url('admin/dates/'.$date->id.'/events');
        $l_event = "<a class=\"verde\" href=\"{$url}\">" . __("Eventos registrados") . "</a>";

        $url = get_url('admin/dates/'.$date->id.'/delete');
        $l_delete = "<a class=\"precaucion\" href=\"{$url}\">" . __("Eliminar") . "</a>";
        
        $table_data[] = array(
            $l_nombre,
            $date->descr,
            $l_event,
            $l_delete
            );
    }

    do_table($table_data, 'wide');

} else {
?>

<div class="block"></div>

<p class="error center"><?=__('No se encontraron lugares registrados.') ?></p>

<?php 
}
?>
