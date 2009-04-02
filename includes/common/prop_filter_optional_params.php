<?php

    // input variables
    $id_prop_tipo = optional_param('filter_id_prop_tipo', 0, PARAM_INT);
    $id_orientacion = optional_param('filter_id_orientacion', 0, PARAM_INT);
    $id_status = optional_param('filter_id_status', 0, PARAM_INT);

    if (Context == 'admin') {
        $id_admin = optional_param('filter_id_adminlogin', 0, PARAM_INT);

        if (!empty($id_admin)) {
            if ($id_admin == -1) {
                $where .= ' AND P.id_administrador=0';
            } else {
                $where .= ' AND P.id_administrador='.$id_admin;
            }
        }

        if (Action == 'listdeletedproposals') {
            $id_ponente = optional_param('filter_id_ponente', 0, PARAM_INT);

            if (!empty($id_ponente)) {
                $where .= ' AND P.id_ponente='.$id_ponente;
            }
        }
    }

    // modify sql where
    if (!empty($id_prop_tipo)) {
        $where .= ' AND P.id_prop_tipo='.$id_prop_tipo;
    }

    if (!empty($id_orientacion)) {
        $where .= ' AND P.id_orientacion='.$id_orientacion;
    }

    if (!empty($id_status)) {
        $where .= ' AND P.id_status='.$id_status;
    }
?>
