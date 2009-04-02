<?php
// die or init user session
if (empty($CFG)) {
    die;
}

// current session user info
global $USER;

switch (Context) {
    case 'admin':
        beginSession('R');
        $sess_id = $_SESSION['YACOMASVARS']['rootid'];
        $USER = get_record('administrador', 'id', $sess_id);
        $CFG->home_url = get_url('admin');
        $CFG->logout_url = get_url('admin/logout');
        //check for databse upgrades
        dbsetup_upgrade();
        break;

    case 'ponente':
        beginSession('P');
        $sess_id = $_SESSION['YACOMASVARS']['ponid'];
        $USER = get_record('ponente', 'id', $sess_id);
        $CFG->home_url = get_url('speaker');
        $CFG->logout_url = get_url('speaker/logout');
        break;

    case 'asistente':
        beginSession('A');
        $sess_id = $_SESSION['YACOMASVARS']['asiid'];
        $USER = get_record('asistente', 'id', $sess_id);
        $CFG->home_url = get_url('person');
        $CFG->logout_url = get_url('person/logout');
        break;

    default:
        die; // if unknown context
}
?>
