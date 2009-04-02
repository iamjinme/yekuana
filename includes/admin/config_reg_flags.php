<?php

if (empty($CFG) || empty($q) || Context != 'admin') {
    die;
}

if ($reg_flags = get_records('config')) {

    $table_data = array();
    $table_data[] = array(__('Nombre'), __('Estado'), __('Acción'));

    foreach($reg_flags as $conf) {
        $status_desc = ($conf->status) ? __('Abierto') : __('Cerrado');
        // toggle status
        $status_toggle = ($conf->status) ? 'close' : 'open';

        $action_desc = ($conf->status) ? __('Cerrar') : __('Abrir');
        $action_class = ($conf->status) ? 'precaucion' : 'verde';

        $url = get_url('admin/config');

        $action = <<< END
<a class="{$action_class}" href="{$url}/{$status_toggle}/{$conf->id}">{$action_desc}</a>
END;

        $table_data[] = array(
                sprintf('<ul><li>%s</li></ul>', $conf->descr),
                $status_desc,
                $action
            );
    }

    do_table($table_data, 'narrow');
}
?>
