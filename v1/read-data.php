<?php

//include vendor
require('../vendor/autoload.php');

use \Firebase\JWT\JWT;

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
	// $data = array();
	//$data = json_decode(file_get_contents("php://input"));

	$all_headers = getallheaders();
	$data = $all_headers['Authorization'];





	if (!empty($data)) {
		try{
			$secret_key = "owt125";
		$decode_data = JWT::decode($data,$secret_key,array('HS512'));

		
		http_response_code(200);
				echo json_encode(array(
					"status" => 1,
					"message" => $decode_data

				));
			}catch(Exception $ex){
				http_response_code(200);
				echo json_encode(array(
					"status" => 1,
					"message" => $ex->getMessage()

				));

			}
	}


}






?>