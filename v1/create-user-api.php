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

	if (!empty($data->name) && !empty($data->mobile)&& !empty($data->reffral) && !empty($data->password)) {

		$user_obj->name = $data->name;
		$user_obj->mobile = $data->mobile;
		$user_obj->password = password_hash($data->password, PASSWORD_DEFAULT);
        $user_obj->reffral = $data->reffral;


		if (!empty($user_obj->check_mobile())) {
			//we have mobile in database --insert should not go
			http_response_code(305);
			echo json_encode(array(
				"status" => 0,
				"message" => "Already you have registered"
			));
		} else {
			$data = $user_obj->check_reffral();
            if (empty($data)) {
            	http_response_code(404);
			 echo json_encode(array(
				"status" => 0,
				"message" => "Referral Mobile Number is not Valid."
			));

            }else{

               if (($data['left'] == null) && ($data['mid'] == null )&& ($data['right'] == null)) {
                 $user_obj->child = "left";

                  }elseif (($data['mid'] == null )&& ($data['right'] == null)) {
                  $user_obj->child = "mid";

                  }elseif (($data['right'] == null)) {
                  	$user_obj->child = "right";
                  }else{
                   http_response_code(303);
				   echo json_encode(array(
					"status" => 0,
		  			"message" => "Your Referral is done"
		     		));
                     exit();

                  }
                   
   //          if ($user_obj->insert_child() && $user_obj->create_user()) {
			// 	http_response_code(200);
			// 	echo json_encode(array(
			// 		"status" => 1,
			// 		"message" => "Registration succesfully"
			// 	));
			// } else {
			// 	http_response_code(201);
			// 	echo json_encode(array(
			// 		"status" => 0,
			// 		"message" => "Failed to save data"
			// 	));
			// }
             $send_res = null;
             $send_res = $user_obj->send_otp();
             $Otpstatus = json_decode($send_res,true);

		    if ($Otpstatus['return']) {
		        http_response_code(200);
				echo json_encode(array(
					"status" => 1,
					"message" => $Otpstatus['message'],
					"otp" => $user_obj->user_otp,
					"slot" =>$user_obj->child
				));
			


		    	}else{
                    http_response_code(201);
		    		echo json_encode(array(
					"status" => 0,
					"message" => $Otpstatus['message'] 
				));	
		    	}	



            }

			
		}
	} else {
		http_response_code(503);
		echo json_encode(array(
			"status" => 0,
			"message" => "All data needed"
		));
	}
} else {
	http_response_code(503);
	echo json_encode(array(
		"status" => 0,
		"message" => "Access Denied"
	));
}
