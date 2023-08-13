<?php
/**
* @author YafarShoes
**/

define("ROOT", dirname(__FILE__));

$debug= false;
if($debug){
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
}
else{
error_reporting(0);

}
include "core/autoload.php";

ob_start();
session_start();
Core::$root="";

// muestre las consultas SQL
// Core::$debug_sql = true;

$lb = new Lb();
$lb->start();

?>