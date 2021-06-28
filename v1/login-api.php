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
	$data = json_decode(file_get_contents("php://input"));



	if (!empty($data->mobile)) {
		$user_obj->mobile = $data->mobile;
		$user_data = $user_obj->check_mobile();

		if (!empty($user_data)) {
			//some data
			$name = $user_data['user_name'];
			$mobile = $user_data['mobile'];
			$password = $user_data['password'];
			if (password_verify($data->password, $password)) {
				
				$iss = "localhost";
				// $iat = time();
				// $nbf = $iat + 60;
				// $exp = $iat + 60;
				$aud = "my users";
				$user_arr_data = array(
					"id" => $user_data['user_id'],
					"name" => $user_data['user_name'],
					"mobile" => $user_data['mobile']
				);

				$secret_key = "owt125";
				$payload_info = array(
					"iss"=>$iss,
					"iat"=>$iat,
					"nbf"=>$nbf,
					"exp"=>$exp,
					"aud"=>$aud,
					"data"=>$user_arr_data

				);
              

				
				$jwt = JWT::encode($payload_info,$secret_key,'HS512');
				http_response_code(200);
				echo json_encode(array(
					"status" => 1,
					"JWT" => $jwt,
					"message" => "Login succesfully"
				));
			} else {
				http_response_code(404);
				echo json_encode(array(
					"status" => 0,
					"message" => "Invailid Password"
				));
			}
		} else {
			http_response_code(545);
			echo json_encode(array(
				"status" => 0,
				"message" => "Invailid Mobile"
			));
		}
	} else {
		http_response_code(545);
		echo json_encode(array(
			"status" => 0,
			"message" => "All data Needed"
		));
	}
} else {
	http_response_code(503);
	echo json_encode(array(
		"status" => 0,
		"message" => "Access Denied"
	));
}
