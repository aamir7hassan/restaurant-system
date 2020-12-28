<?php
error_reporting(1);
defined("__EXEC__") or die("Cannot access file directly!");
require_once ('../signup/database/MysqliDb.php');
require_once 'inc/functions.php';
    
$accounts = accounts();

if(isset($_GET['action']) && isset($_GET['id']) && in_array($_GET['action'], array('enable', 'delete', 'disable', 'duplicate', 'update', 'option1', 'option2', 'option3', 'option4', 'delivery', 'reservation')) && is_numeric($_GET['id']) ){
    
    $id     = $_GET['id'];
    $action = $_GET['action'];
	$val = isset($_GET['delivery'])?$_GET['delivery']:"";
	$reserv = isset($_GET['reservation'])?$_GET['reservation']:"";
	$sku = isset($_GET['sku'])?$_GET['sku']:"";
	switch ($action){
        case 'enable' :
            $result = enable($id);    
            break;
        case 'disable' :
            $result = disable($id);
            break;
        case 'delete' :
            $result = delete($id);
            break;
        case 'duplicate':
            $result = duplicate($id);
            break;
        case 'update' :
            $result = update($id);
            break;
        case 'option1' :
            $result = option1($id);
            break;
        case 'option2' :
            $result = option2($id);
            break;
        case 'option3' :
            $result = option3($id);
            break;
        case 'option4' :
            $result = option4($id);
            break;
		case 'delivery':
			$result = delivery($id,$val,$sku);
			break;
		case 'reservation':
			$result = reservation($id,$reserv,$sku);
			break;
    }
   
    $_SESSION['message'] = $result;
    header("Location: index.php");
}