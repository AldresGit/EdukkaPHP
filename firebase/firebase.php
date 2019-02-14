<?php

class Firebase {
	
	public function send($to, $message) {
		$fields = array(
			'to' => $to,
			'data' => $message,
		);
		return $this->sendPushNotification($fields);
	}
	
	private function sendPushNotification($fields) {
		
		//require_once __DIR__ . '/firebase/config.php';
		
		$url = 'https://fcm.googleapis.com/fcm/send';
		
		$headers = array(
			'Authorization: key=AAAAEFuurP8:APA91bGDooFP7nN28oMIpoaq5ZTQn0UtjTjbEJVy7xSel_JjDWWqQmCVL9NXpMWIAWdP3ghzv2mwXAfRmLaCoNLu0E3RxRxNfWJSIxhyxy2s0Wt6x13ny0hwAtBrz2pnB3HcovhgxtKS',
			'Content-Type: application/json'
		);
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		
		$result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
 
        // Close connection
        curl_close($ch);
 
        return $result;
	}
	
}

?>