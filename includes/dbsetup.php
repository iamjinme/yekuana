<?php
global $db, $METATABLES;

/// Check if the main tables have been installed yet or not.
if (!$METATABLES) {    // No tables yet at all.
    $maintables = false;
} else {
    $maintables = false;
    $datalists = false;
    foreach ($METATABLES as $table) {
        if (preg_match("/^{$CFG->prefix}administrador/", $table)) {
            $maintables = true;
        }
        if (preg_match("/^{$CFG->prefix}datalists$/", $table)) {
            $datalists = true;
        }
    }
}

$strdatabasesuccess = "Yay!"; // well, if people leave never-defined variables about the place...

if (!$maintables) {
    if (file_exists($CFG->incdir . "db/{$CFG->dbtype}.sql")) {
        $db->debug = true;
        
        //version check
        $continue = true;
        $infoarr = $db->ServerInfo();
        if (!empty($infoarr['version'])) {
            switch($CFG->dbtype) {
                case "mysql":
                    if (!preg_match('/^(4\.1|[5-9]\.|[0-9][0-9]+)/', $infoarr['version'])) {
                        show_error('Error: Your MySQL version is too old: ' . $infoarr['version'] . '. Required MySQL 4.1 or newer. 5.0 or newer is recommended.', false);
                        $continue = false;
                    }
                break;
            }
        }
        
        if ($continue) {
            if (modify_database($CFG->incdir . "db/{$CFG->dbtype}.sql")) {
                include_once($CFG->incdir . "version.php");
                set_config('version', $version);
                $db->debug = false;
                notify($strdatabasesuccess, "verde");
                if (!isset($CFG->admininitialpassword) || empty($CFG->admininitialpassword)) {
                    notify("WARNING: the initial password for the admin account is 'admin'. This account has administrator privileges, and you should log in and change the password as soon as installation is complete.");
                } else {
                    set_field('administrador', 'passwd', md5($CFG->admininitialpassword), 'login', 'admin');
                }
            } else {
                $db->debug = false;
                show_error("Error: Main databases NOT set up successfully", false);
            }
        }
    } else {
        show_error("Error: Your database ($CFG->dbtype) is not yet fully supported.", false);
    }
    // dont use gettext because is not defined to this point
    do_submit_cancel('', 'Continue', get_url());
    die;
}

// function called when admin login to check db upgrades
function dbsetup_upgrade () {
    global $USER, $CFG, $datalists, $strdatabasesuccess;

    if (Context == 'admin' && $USER->login == 'admin' && $USER->id_tadmin == 1) {
        
        if (empty($CFG->version)) {
            $CFG->version = 1;
        }

        if (empty($CFG->release)) {
            $CFG->release = "";
        }

        if (!$datalists) {
            $CFG->version = -1;
        }

        /// Upgrades
        include_once($CFG->incdir . "version.php");              # defines $version
        include_once($CFG->incdir . "db/{$CFG->dbtype}.php");  # defines upgrades

        if ($CFG->version) {
            if ($version > $CFG->version) {  // upgrade

                $a->oldversion = "$CFG->release ($CFG->version)";
                $a->newversion = "$release ($version)";

                if (empty($_GET['confirmupgrade'])) {
                    notify(__('Su base de datos necesita actualizarse.'));
                    do_submit_cancel('', __('Actualizar'), get_url('admin') . '&confirmupgrade=yes');
                    exit;

                } else {
                    $db->debug=true;
                    if (main_upgrade($CFG->version)) {
                        $db->debug=false;
                        if (set_config("version", $version)) {
                            notify($strdatabasesuccess, "green");
                            do_submit_cancel('', __('Continuar'), get_url('admin'));
                            exit;
                        } else {
                            notify(__("La actualización falló!  (No se puede actualizar la version en la tabla de configuración)"));
                        }
                    } else {
                        $db->debug=false;
                        notify(__("La actualización falló!  Vea /includes/version.php"));
                    }
                }
            } else if ($version < $CFG->version) {
                notify(__("ADVERTENCIA!!!  El código que estas usando es más ANTIGUO que la version que instalo la base de datos!"));
            }

        } else {
            if (set_config("version", $version)) {
                do_submit_cancel('', __('Continuar'), get_url('admin'));
                die;
            } else {
                $db->debug=true;
                if (main_upgrade(0)) {
                    do_submit_cancel('', __('Continuar'), get_url('admin'));
                } else {
                    show_error(__("Ocurrió un problema al insertar la versión actual en la base de datos"), false);
                }
                $db->debug=false;
            }
        }

    }

}

?>
