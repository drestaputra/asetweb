<?php

class AUTHORIZATION
{
    public static function validateTimestamp($token)
    {
        $CI =& get_instance();
        $token = self::validateToken($token);
        if ($token != false && (now() - $token->timestamp < ($CI->config->item('token_timeout') * 60))) {
            return $token;
        }
        return false;
    }

    public static function validateToken($token)
    {
        $CI =& get_instance();
        return JWT::decode($token, $CI->config->item('jwt_key'));
    }
    public static function validateTokenUser($token)
    {
        $CI =& get_instance();
        $decoded = JWT::decode($token, $CI->config->item('jwt_key'), array('HS256'));
        
        $decoded = (array) $decoded;         
        $is_valid = false;
        $id_user = (isset($decoded['id_user']) AND !empty($decoded['id_user'])) ? $decoded['id_user'] : "0";
        $username = (isset($decoded['username']) AND !empty($decoded['username'])) ? $decoded['username'] : "0";
        $token_expired = (isset($decoded['token_expired']) AND !empty($decoded['token_expired'])) ? $decoded['token_expired'] : "0";
        $password = (isset($decoded['password']) AND !empty($decoded['password'])) ? $decoded['password'] : "0";
        $password = hash('sha512',$password . config_item('encryption_key'));        
        $valid = $CI->function_lib->get_one('id_user','user','username='.$CI->db->escape($username).' AND password='.$CI->db->escape($password).' AND status="aktif"');        
        if (!empty($valid)) {
             $is_valid = true;
        } else{
             $is_valid = false;
        }
        return $is_valid;
    }

    public static function generateToken($data)
    {
        $CI =& get_instance();
        return JWT::encode($data, $CI->config->item('jwt_key'));
    }
    public static function verify_request()
    {
        $CI =& get_instance();
        // Get all the headers
        $headers = $CI->input->request_headers();
        // Extract the token
        if (!isset($headers['Authorization'])) {
            $response = ['status' => 401, 'msg' => 'Unauthorized Access! '];
            echo json_encode($response);
            exit();
        }
        $token = $headers['Authorization'];
        // Use try-catch
        // JWT library throws exception if the token is not valid
        try {
            // Validate the token
            // Successfull validation will return the decoded user data else returns false
            $data = AUTHORIZATION::validateToken($token);
            if ($data === false) {
                $status = 401;
                $response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
                $CI->response($response, $status);
                exit();
            } else {
                return true;
            }
        } catch (Exception $e) {
            // Token is invalid
            // Send the unathorized access message
            $status = 401;
            $response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
            $CI->response($response, $status);
        }
    }
    public static function get_id_user()
    {

        $CI =& get_instance();
        // Get all the headers
        $headers = $CI->input->request_headers();
        // Extract the token
        if (!isset($headers['Authorization'])) {
            $response = ['status' => 401, 'msg' => 'Unauthorized Access! '];
            echo json_encode($response);
            exit();
        }
        $token = $headers['Authorization'];
        $decoded = JWT::decode($token, $CI->config->item('jwt_key'), array('HS256'));
        $decodedArr = (array) $decoded;   
        $id_user = (isset($decodedArr['id_user']) AND !empty($decodedArr['id_user'])) ? $decodedArr['id_user'] : "0";
        $username = (isset($decodedArr['username']) AND !empty($decodedArr['username'])) ? $decodedArr['username'] : "";
        $password = (isset($decodedArr['password']) AND !empty($decodedArr['password'])) ? $decodedArr['password'] : "";
        $password = hash('sha512',$password . config_item('encryption_key'));        
        $id_user = $CI->function_lib->get_one('id_user','user','username="'.$username.'" AND password="'.$password.'" AND status="aktif"');
        return $id_user;
    }
    public static function check_token()
    {
        $CI =& get_instance();
        // Get all the headers
        $headers = $CI->input->request_headers();
        // Extract the token
        if (!isset($headers['Authorization'])) {
            $response = ['status' => 401, 'msg' => 'Unauthorized Access! '];
            echo json_encode($response);
            exit();
        }
        $token = $headers['Authorization'];
        
        // Use try-catch
        // JWT library throws exception if the token is not valid
        try {
            // Validate the token
            // Successfull validation will return the decoded user data else returns false
            $data = AUTHORIZATION::validateTokenUser($token);
            if ($data === false) {
                $status = 401;
                $response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
                echo json_encode($response);
                exit();
            } else {
                return true;
            }
        } catch (Exception $e) {
            // Token is invalid
            // Send the unathorized access message
            $status = 401;
            $response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
            echo json_encode($response);
            exit();
        }
    }

}