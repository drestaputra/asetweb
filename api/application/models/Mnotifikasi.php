<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Mnotifikasi extends  CI_Model {
    function __construct()
    {
        parent::__construct();
        
    } 
    // Sending message to a topic by topic name
    // $message = array("title"=>$title,"message"=>$messageNotif,"tag"=>$key,"news_permalink"=>$value['news_permalink']);
    public function sendToTopic($to, $message) {
        $fields = array(
            'notification' => array("title"=>$message['title'],"body"=>$message['message']),
            'to' => '/topics/' . $to,
            'data' => array(
                "page"=>$to,
                "news_permalink"=> isset($message['news_permalink']) ? $message['news_permalink'] : "",
                "click_action"=> "ANDROID_NOTIFICATION_CLICK",
            ),
        );
        return $this->sendPushNotification($fields);
    }
       
 
    // function makes curl request to firebase servers
    private function sendPushNotification($fields) {
        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = "AAAA-AzksY0:APA91bElxepqf6Z10Xzmkp8dTCV2cYNlKiB1EAIyM5MVG3XrPPdS-zWweI2vnULKNW66WW2eVsyo_Hi2ldjJOPHxDBbUoHudv8SoiDYustDMTjeEEYV4yYh0Wjo8Rne596PobomMU1Wp";
 
        $headers = array(
            'Authorization: key=' . $api_key,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);          
        return array("result"=>$result,"param"=>$fields);
    }
    
}