<?php

define("REQUIRED_FIELD","This Field is required");

function validate_user($info,&$errors){
    $valid=true;

    $name=  test_input($info['user-name']);
    $email=test_input($info['email']);
    $password=test_input($info['password']);
    $confirm_pass=test_input($info['confirm-pass']);
    $phone=test_input($info['phone']);
    $valid&=validate_name($name,$errors);
    $valid&=validate_email($email,$errors);
    $valid&=validate_phone($phone,$errors);
    $valid&=validate_password($password,$confirm_pass,$errors);
    return $valid;
}
function get_all_users(){
    $users=[];
if(file_exists("users/users.json")){
    $users = json_decode(file_get_contents('users/users.json'), true);
}
return $users;
}
// 

function validate_name($name ,&$errors){
    $valid=true;
    if(!$name){
        $errors['user-name']=REQUIRED_FIELD;
        $valid=false;
    }
    if( strlen($name)<6 || strlen($name)>16 ){
        $errors['user-name']="Min 6 and Max 16 char";
        $valid=false;
    }
    if(!preg_match("/^[a-zA-Z ]+$/", $name) ){
        $errors["user-name"]= "Name must be only chars";
        $valid=false;
    }
    return $valid;
}
    

function validate_email($email,&$errors){
    $pattern="/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    $valid=true;
    if(!$email){
        $errors["email"]=REQUIRED_FIELD;
    }
    if(!preg_match($pattern, $email)){
        $valid=false;
        $errors["email"]= "Invalid Email";
    }
    return $valid;
}
 function validate_phone($phone,&$errors){
    $valid=true;
    if(!$phone){
        $valid=false;
        $errors["phone"]=REQUIRED_FIELD;
    }
    if(strlen($phone)< 10){
        $valid=false;
        $errors["phone"]= "must be 10:15 digits";
    }
  if (!preg_match("/^\d+$/", $phone) ){
    $valid=false;
    $errors["phone"]= "Phone Number must be only digits 0:9";
  } 
  return $valid;
 }
function validate_password($pass, $confirm_pass,&$errors){
    $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/';
    $valid=true;
   if(!$pass){
    $valid=false;
    $errors["password"]=REQUIRED_FIELD;
   }
   if(!$confirm_pass){
    $valid=false;
    $errors["confirm-pass"]=REQUIRED_FIELD;
   }
   if(strlen($pass)< 8){
    $valid=false;
    $errors["password"]= "must be 8 or more ";
   }
   if(!preg_match($pattern, $pass)){
    $valid=false;
    $errors["password"]= "It must have uppercase and lowercase letters, numbers and special signs";
   }
   if($pass!=$confirm_pass){
    $valid=false;
    $errors["confirm-pass"]= "Must match password";
   }
   return $valid;
}
 function putJSON($data){
    $users=get_all_users();
    $users[]=$data;
    file_put_contents('users/users.json',json_encode($users,JSON_PRETTY_PRINT));
  }


function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}