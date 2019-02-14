<?php 
include_once("Medoo.php");
define("DB_HOST", "localhost");
define("DB_USERNANE", "root");
define("DB_PASS", "P@ssw0rd");
define("DB_SCHEM", "student_helper");
define("DB_PORT", "3360");

use Medoo\Medoo;

$database = new Medoo([
    // required
    'database_type' => 'mysql',
    'database_name' => DB_SCHEM,
    'server' => DB_HOST,
    'username' => DB_USERNANE,
    'password' => DB_PASS,
    // [optional]
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
    'port' => 3306,
]);
?>