<?php
// User info
$db_host = '';
$db_user = '';
$db_pass = '';
$db_base = '';

// Connect database
$db_conn = new mysqli($db_host, $db_user, $db_pass, $db_base);
// If connect error
if($db_conn->connect_error) die('Connect error: ' . $db_conn->connect_error);