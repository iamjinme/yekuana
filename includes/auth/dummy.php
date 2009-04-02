<?php
// dummy auth file
//
// Can filter $context to use diferent auth schema for admin, ponente or 
// asistente. login must exists in context dbtable to start session.
//
// Return true if auth success
//
function dummy_user_auth ($login, $pass, $context) {
    //login with any pass
    return true;
}

?>
