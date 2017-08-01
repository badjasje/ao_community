<?php
// API access key from Google API's Console
define( 'API_ACCESS_KEY', 'YOUR_FIREBASE_API_ACCESS_KEY' );
$registrationIds = array( 'AIzaSyBBkuM6n38eUe5yqw50KjpM7HHAR2RGdOQ' );
// prep the bundle
$msg = array
(
	'body' 	=> $_GET['body'],
	'title'		=> $_GET['title'],
	'vibrate'	=> 1,
	'sound'		=> 1,
);
$fields = array
(
	'registration_ids' 	=> $registrationIds,
	'notification'			=> $msg
);
 
$headers = array
(
	'Authorization: key=' . 'AAAAtMYygfc:APA91bEMDKTi556dx98bDJRF0KoG4IiG6L5xfiYvxOcRDL2yFWKhvnEwpqS-JHbLkUTdpmNqbQT0nn7mAt0B4ftxBQ6-zrI_yM_cWzwjLoTH-t51aCILfKbG_l6BcltB3MkGx6Yh9XBW',
	'Content-Type: application/json'
);
 
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
echo $result;
?>