<?php
    // running from system?
    if (empty($CFG) || empty($proposal)) {
        die;
    }
   
    $files = get_records('prop_files', 'id_propuesta', $proposal->id);

    if (empty($files)) {
?>

<p class="error center"><?=__('Esta propuesta no tiene archivos adjuntos.') ?></p>

<?php
    } else {
        
        $table_data = array();
        // headers
        $table_data[] = array(__('Nombre'), __('Descripción'), __('Tamaño'), __('Público'), '');

        foreach ($files as $f) {
            $public = (empty($f->public)) ? __('No') : __('Si');

            //download file
            $url = get_url('speaker/proposals/'.$proposal->id.'/files/'.$f->id.'/'.$f->name);
            $l_name = "<a href=\"{$url}\">{$f->title}</a>";

            //size 
            $size = sprintf('<span class="right">%s</span>', human_filesize($f->size));

            $sMod = __('Modificar');
            $sDelete = __('Eliminar');

            $url = get_url('speaker/proposals/'.$proposal->id.'/files/edit/'.$f->id.'/'.$f->name);
            $l_modify = "<a class=\"verde\" href=\"{$url}\">{$sMod}</a>";

            if ($proposal->id_status < 5) {
                $url = get_url('speaker/proposals/'.$proposal->id.'/files/delete/'.$f->id.'/'.$f->name);
                $l_delete = "&nbsp;|&nbsp;<a class=\"precaucion\" href=\"{$url}\">{$sDelete}</a>";
            } else {
                $l_delete = '';
            }

            $table_data[] = array(
                $l_name,
                $f->descr,
                $size,
                $public,
                $l_modify.$l_delete
                );
        }

        do_table($table_data, 'narrow-form files');
    }

?>
