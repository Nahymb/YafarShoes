<?php
//print_r($_POST);
if(!empty($_POST)){
	$cat =  CategoryData::getById($_POST["_id"]);
	$cat->category_id = $_POST["category_id"];
	$cat->name = $_POST["name"];
	$cat->short_name = $_POST["short_name"];
	if(isset($_POST["is_active"])){ $cat->is_active=1;}else{$cat->is_active=0;}
	$cat->update_sub();

	Core::redir("index.php?view=subcategories");
}
?>