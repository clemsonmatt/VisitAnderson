<?php
/**
 * Config file hold all database connections
 */

date_default_timezone_set('America/New_York');

/* you local machine */
if (getenv('REMOTE_ADDR') == '127.0.0.1') {
    $dbhost = '127.0.0.1';
    $dbname = 'visit_anderson';
    $dbuser = 'root';
    $dbpass = null;
}
/* remote connection */
else {
    $dbhost = 'visitanderson.db.11185848.hostedresource.com';
    $dbname = 'visitanderson';
    $dbuser = 'visitanderson';
    $dbpass = 'Database1!';
}

$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
