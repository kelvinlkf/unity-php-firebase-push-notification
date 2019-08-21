# Firebase Push Notification In Unity Using PHP

For source code, please download the file and you are ready to go.
<br>
For firebase setup and information, please follow the steps below.


# Setup Firebase Project

Please follow the link to setup your project if you have not created one yet. https://firebase.google.com/docs/unity/setup
<br>

# Get Device Token In Unity
Link: https://firebase.google.com/docs/cloud-messaging/unity/client

# Install And Test
Once you finished setup your unity and firebase project, compile it inot package and install in one of your device for testing.

<br>

In order to send a notification using the php file above, a receiver token is required to identify which device will be receiving the notification.

<br>

For instance, we can get our token from the function below
```c#
    public void OnTokenReceived(object sender, Firebase.Messaging.TokenReceivedEventArgs token)
    {
      UnityEngine.Debug.Log("Received Registration Token: " + token.Token);
    }
```

Note that the *token.Token* will be the device token that we need to store as device identifier for our push notification later.
<br>

Once we get the device token we need, we will be sending the push notification to our target which consist of title, body and token via the php file hosted in our server. In this case, we will be utilizing Unity Web Request which can be found in Unity.Networking namespace

```c#
using UnityEngine.Networking

public IEnumerator DeliverMessage()
    {
        WWWForm webForm = new WWWForm();

        webForm.AddField("recipient", "<DEVICE TOKEN>");
        webForm.AddField("title", "<TITLE>");
        webForm.AddField("body", "<MESSAGE>");

        using (UnityWebRequest web = UnityWebRequest.Post(webServer + "SendNotification.php", webForm))
        {
            yield return web.SendWebRequest();

            if(web.error == null)
            {
            }
            else
            {
                // You can add follow up code here if the message is send successfully
            }
        }
    }
```

Before we run the function, we should check the php file contained the server key from our google firebase project. Read Below

# Inspect The PHP Code

We are using the REST API provided by Google Firebase here
```php
<?php
	//Sets the serverKey variable that can be obtained from your firebase project setting > Cloud Messaging > Server Key
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
?>
```
