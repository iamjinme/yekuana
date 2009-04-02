<?php
// dummy check
if (empty($CFG) || Context != 'admin') {
    die;
}

$rooms = get_records('lugar');
?>

<h1><?=__('Listado de lugar para eventos') ?></h1>

<?php
if (!empty($rooms)) {
?>

<h4><?=__('Lugares registrados') ?>: <?=sizeof($rooms) ?></h4>

<?php
    // build data table
    $table_data = array();
    $table_data[] = array(__('Nombre'), __('Ubicación'), __('Disp.'), '', '');

    foreach ($rooms as $room) {

        $url = get_url('admin/rooms/'.$room->id);
        $l_nombre = <<< END
<ul><li>
<a class="speaker" href="{$url}">{$room->nombre_lug}</a>
</li></ul>
END;

        $capacidad = (empty($room->cupo)) ? '--' : $room->cupo;

        $url = get_url('admin/rooms/'.$room->id.'/events');
        $l_event = "<a class=\"verde\" href=\"{$url}\">" . __("Eventos registrados") . "</a>";

        $url = get_url('admin/rooms/'.$room->id.'/delete');
        $l_delete = "<a class=\"precaucion\" href=\"{$url}\">" . __("Eliminar") . "</a>";
        
        $table_data[] = array(
            $l_nombre,
            $room->ubicacion,
            $capacidad,
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
