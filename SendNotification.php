<?php
	
	//Includes the file that contains your project's unique server key from the Firebase Console.
	require_once("serverKeyInfo.php");
	
	//Sets the serverKey variable to the googleServerKey variable in the serverKeyInfo.php script.
	$serverKey = "<WEB SERVER KEY THAT OBTAINED FROM PROJECT SETTINGS>";
	
	//URL that we will send our message to for it to be processed by Firebase.
	$url = "https://fcm.googleapis.com/fcm/send";
	
	//Recipient of the message. This can be a device token (to send to an individual device) 
	//or a topic (to be sent to all devices subscribed to the specified topic).
	//Here we use POST to obtain the recipient that we specified in Unity.
	$recipient = $_POST['recipient'];
	
	//Structure of our notification that will be displayed on the user's screen if the app is in the background.
	$notification =
	[
		'title'   => $_POST['title'],
		'body'  =>  $_POST['body']
	];

	//Structure of the data that will be sent with the message but not visible to the user.
	//We can however use Unity to access this data.
	$dataPayload = 
	[
		"Sample Data 1" => "This is data 1",
		"Sample Data 2" => "This is data 2"
	];
	
	//Full structure of message inculding target device(s), notification, and data.
	$fields = 
	[
		'to'  => $recipient,
		'notification' => $notification,
		'data' => $dataPayload
	];

	//Set the appropriate headers
	$headers = 
	[
	'Authorization: key=' . $serverKey,
	'Content-Type: application/json'
	];

	//10 second delay in PHP script.
	//This is used for testing purposes to give you some time to exit the application to see the notification
	//appear if your are sending a message to the device you are sending from.
	
	//Send the message using cURL.
	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, $url);
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );
	curl_close( $ch );
	
  //Result is printed to screen.
  //Can Remove To Hide Response
	echo $result;
	echo $fields["to"];
?>