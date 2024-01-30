
<?php
require_once("password.php");
/**
 * Configuration for database connection
 *
 */
function configure( $host, &$username, &$password, &$options, $dbname, &$dsn){
    $host = $host ? $host : "csc471.uncg.edu";
    $options = $options ? $options : array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION );
    $dbname = $dbname ? $dbname : "ecmacias_project";
    //$dbname = "University";
    $dsn        = "mysql:host=$host;dbname=$dbname";
}
?>