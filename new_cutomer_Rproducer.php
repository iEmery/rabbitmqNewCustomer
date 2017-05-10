<?php
session_start();

//WAIT FOR NOTIFY

//NEW CUSTOMER
$user_id = 0;
if (isset($_POST['user'])) {
	$api_key = '6e9f65683a730ab086704e0e924a26c9314805aaea4508c54a1ab6268c02cc';
	$user_id = $_POST['user'];
	$url = 'www.planyo.com/rest/?api_key='.$api_key . '&method=list_reservations&user_id='. $user_id;


	//user
	$client = curl_init($url);
	$response = curl_exec($client);
	$result = json_decode($response);
	$data = $result->data;

	if ($data->is_email_verified = true) {
		//rabbitmq
		$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
		$channel = $connection->channel();
		$channel->queue_declare('newCRMuser', false, false, false, false);
		$channel->basic_publish($data, '', 'newCRMuser');

		$channel->close();
		$connection->close();
	}

}
