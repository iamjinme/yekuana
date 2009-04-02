<?php

    // input variables
    $id_estado = optional_param('filter_id_estado', 0, PARAM_INT);
    $search_lastname = optional_param('filter_search_lastname');

    if (Action == 'controlpersons') {
        $id_tasistente = optional_param('filter_id_tasistente', 0, PARAM_INT);
    } else {
        $id_estudios = optional_param('filter_id_estudios', 0, PARAM_INT);
    }

    // modify sql where
    if (!empty($search_lastname)) {
        if (Action == 'controlpersons' || Action == 'listpersons') {
            $where .= ' AND P.apellidos LIKE "%' . $search_lastname . '%"';
        }
    }

    if (!empty($id_estado)) {
        if (Action == 'listspeakers') {
            $where .= ' AND SP.id_estado='.$id_estado;
        }

        elseif (Action == 'listpersons') {
            $where .= ' AND P.id_estado='.$id_estado;
        }
    }

    if (!empty($id_estudios)) {
        if (Action == 'listspeakers') {
            $where .= ' AND SP.id_estudios='.$id_estudios;
        }

        elseif (Action == 'listpersons') {
            $where .= ' AND P.id_estudios='.$id_estudios;
        }
    }

    if (!empty($id_tasistente)) {
        if (Action == 'controlpersons') {
            $where .= ' AND P.id_tasistente='.$id_tasistente;
        }
    }
?>
