<?php
ini_set('display_errors', '1');
//includes header
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods:POST");
header("Content-type:application/json;charst=UTF-8");
//include files


include_once("../config/Database.php");
include_once("../classes/Users.php");

//objects
$db = new Database();
$cnnection = $db->connect();
$user_obj = new Users($cnnection);


if ($_SERVER['REQUEST_METHOD'] === "POST") {
	$data = json_decode(file_get_contents("php://input"));

	// if (!empty($data->name) && !empty($data->mobile)&& !empty($data->reffral) && !empty($data->password)) {

		$user_obj->name = $data->name;
		$user_obj->mobile = $data->mobile;
		$user_obj->password = password_hash($data->password, PASSWORD_DEFAULT);
        $user_obj->reffral = $data->reffral;
        $user_obj->child = $data->slot;


                  if ($user_obj->insert_child() && $user_obj->create_user()) {
				http_response_code(200);
				echo json_encode(array(
					"status" => 1,
					"message" => "Registration succesfully"
				));
			} else {
				http_response_code(201);
				echo json_encode(array(
					"status" => 0,
					"message" => "Failed to save data"
				));
			}		
		
	
} else {
	http_response_code(503);
	echo json_encode(array(
		"status" => 0,
		"message" => "Access Denied"
	));
}
