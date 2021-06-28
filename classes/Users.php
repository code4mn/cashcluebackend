<?php

class Users{

//user data
public $name;
public $moble;
public $password;
public $reffral;
public $child;

//user Otp
public $user_otp;
// connect variable & usertable
private $conn;
private $user_table;

//constructor
public function __construct($db){
	$this->conn = $db;
	$this->user_table ="user";
}

//create user entry
public function create_user(){
$sql = "INSERT INTO $this->user_table (user_name, mobile, password) VALUES (?, ?, ?)";
$user_obj = $this->conn->prepare($sql);

$user_obj->bind_param("sss",$this->name,$this->mobile,$this->password);
  
if ($user_obj->execute()) {
	return true;

}

return false;
}


//check Mobile
public function check_mobile(){

$m_sql = "SELECT * FROM $this->user_table WHERE mobile = ? ";

$mobile_obj = $this->conn->prepare($m_sql);
$mobile_obj->bind_param("s",$this->mobile);

if ($mobile_obj->execute()) {
   $data = $mobile_obj->get_result();
   return $data->fetch_assoc();

}

return array();
	
}
//cehck user reffral existance
public function check_reffral(){

$m_sql = "SELECT * FROM $this->user_table WHERE mobile = ? ";

$mobile_obj = $this->conn->prepare($m_sql);
$mobile_obj->bind_param("s",$this->reffral);

if ($mobile_obj->execute()) {
   $data = $mobile_obj->get_result();
   $mobile_obj->close();
   return $data->fetch_assoc();

    
}
$mobile_obj->close();
return array();
	
}


//insert child L M R

public function insert_child(){
$sql = "UPDATE $this->user_table SET `$this->child` = ? WHERE mobile = ?";

$mo_obj = $this->conn->prepare($sql);
$mo_obj->bind_param("ss",$this->mobile,$this->reffral);

if ($mo_obj->execute()) {
 return true;
}
return false;	
}



//send otp

public function send_otp(){
	  $mobile=$this->mobile;
	  $otp = rand(1000,9999);
	  

  $field = array(
    // "sender_id" => "FSTSMS",
    // "language" => "english",
    // "route" => "qt",
    
    // "message" => "42238",
    // "variables" => "{#AA#}",
    // "variables_values" => "$otp"


       "route" => "v3",
"sender_id" => "TXTIND",
"message" => "Dear Customer, Your Cashclue One Time Password (OTP) is ". $otp . ". Please Enter To Prceed",
"language" => "english",
"flash" => 0,
 "numbers" => "$mobile",
);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode($field),
  CURLOPT_HTTPHEADER => array(
    "authorization: I4x8HVdwHBia4bhb0UTpG1c2JW1baBkaq9tXMuFb4eCut1bbiTQTMk3kNPmq",
    "cache-control: no-cache",
    "accept: */*",
    "content-type: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);


if ($err) {
  return "cURL Error #:" . $err;
} else {
  $this->user_otp = $otp;
  return($response);
  
}
}


//Resend otp

public function resend_otp(){
    $mobile=$this->mobile;
    $otp =  $this->user_otp;
    

  $field = array(
"route" => "v3",
"sender_id" => "TXTIND",
"message" => "Dear Customer, Your OTP is ". $otp . ". Please Enter To Prceed",
"language" => "english",
"flash" => 0,
 "numbers" => "$mobile",
);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode($field),
  CURLOPT_HTTPHEADER => array(
    "authorization: I4x8HVdwHBia4bhb0UTpG1c2JW1baBkaq9tXMuFb4eCut1bbiTQTMk3kNPmq",
    "cache-control: no-cache",
    "accept: */*",
    "content-type: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);


if ($err) {
  return "cURL Error #:" . $err;
} else {
  $this->user_otp = $otp;
  return($response);
  
}
}



} 

?>