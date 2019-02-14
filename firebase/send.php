<?php

	require_once __DIR__ . '/firebase.php';
	require_once __DIR__ . '/push.php';
	
	$firebase = new Firebase();
	$push = new Push();
	
	$payload = array();
	$payload['type'] = 'Comunication';

	// notification title
	$title = 'Notification';
	
	// notification message
	$message = $_POST["message"];
	
	// push type - single user / topic
	$push_type = 'individual';
	
	// whether to include to image or not
	$include_image = '';
	
	$push->setTitle($title);
	$push->setMessage($message);
	$push->setIsBackground(FALSE);
	$push->setPayload($payload);

	$json = '';
	$response = '';
	
	$json = $push->getPush();
	$regId = $_POST["firebase_id"];
	//$regId = 'fiH6k7SHf5E:APA91bHF9upAAMYswBUnS6JwhA1ACQmHhSHCaMQ5nurF3l0jBzXd6ZOhaWLx1t1X5lQTTEUXky8TLlDnWEKZxuxgG5InU6L-zS0QBYjBZJaleY2knwAdtW7LxxLvwtuPudXpQj9BooX-';
	$response = $firebase->send($regId, $json);
?>