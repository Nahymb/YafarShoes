<?php

$cat =  new ShipData();
$cat->name = $_POST["name"];
$cat->description = $_POST["description"];
$cat->price = $_POST["price"];

if(isset($_POST["is_active"])){ $cat->is_active=1;}else{$cat->is_active=0;}
$cat->add();

Core::redir("index.php?view=ships");

?>