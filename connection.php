<?php
/* these variables will use for connection to mysql across all pages */
$server = 'localhost';
$username = 'root';
$password = '';
$dbname = 'pro';
$con = new MySQLi( $server, $username, $password, $dbname );
?>