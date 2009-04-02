<?php
    $id_tadmin = optional_param('filter_id_tadmin', 0, PARAM_INT);

    if (!empty($id_tadmin)) {
        $where .= ' AND ADM.id_tadmin='.$id_tadmin;
    }
?>
