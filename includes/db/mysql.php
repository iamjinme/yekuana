<?php
//
// This file keeps track of upgrades
//
// This file works with mysql

function main_upgrade($oldversion=0) {
    global $CFG, $db, $METATABLES;
    $result = true;

    if ($oldversion < 2007062601) {
        modify_database("", "
            CREATE TABLE `{$CFG->prefix}extauth_hash` (
              `id` int(11) NOT NULL auto_increment,
              `login` varchar(15) NOT NULL default '',
              `hash` varchar(32) NOT NULL default '',
              `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`));");

        $METATABLES = $db->Metatables(); // table added/removed without using modify_database()
        // set initial value for auth config
        set_config('auth', '');
    }
   
    if ($oldversion < 2007062602) {
        table_column('ponente', 'domicilio', 'domicilio', 'varchar', '255', '', '');
    }

    return $result;
}
?>
