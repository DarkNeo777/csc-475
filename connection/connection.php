<PRE>
<?php
try  {
    require_once("dbconfig.php"); //database access details

    //Populate these four variables
    $host = "csc471.uncg.edu";//Domain name of database server
    $dbname = "ecmacias_project";//name of your database
    $username = "ecmacias";//SQL user
    $options = null;

    configure($host, $username, $password, $options, $dbname, $dsn);

    $connection = new PDO($dsn, $username, $password, $options); //create database connection and get handler
    echo "Connected successfully ";

} catch(PDOException $error) {
    //if connection failed, print error and exit;
    echo "Database connection error: " . $error->getMessage() . "<BR>";
    die;
}
?>
</PRE>
    vLjqi\~_FR=LGzv