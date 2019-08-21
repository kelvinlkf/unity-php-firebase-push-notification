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
