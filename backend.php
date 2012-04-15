<?php

// Gather and check the client side input
$id = basename(@$_GET['id']);
$user = @$_SERVER['PHP_AUTH_USER'];
$event_file = dirname(__FILE__) . '/data/' . $id . '.json';

if ( empty($id) or empty($user) )
	die('ID and HTTP basic auth user is required');

// Helper function to safely read and update the data (locks the file to avoid race conditions)
function read_and_update_data($file, $handler){
	$initialized_data = false;
	
	$fd = fopen($file, 'c+');
	flock($fd, LOCK_EX);
	
	$data = json_decode( stream_get_contents($fd), true );
	if ($data == null){
		$data = array('title' => null, 'users' => array());
		$initialized_data = true;
	}
	
	$updated_data = $handler($data);
	
	if ($updated_data or $initialized_data){
		ftruncate($fd, 0);
		fseek($fd, 0);
		fwrite($fd, json_encode($updated_data));
	}
	
	flock($fd, LOCK_UN);
	fclose($fd);
}


if ( isset($_GET['context']) and $_GET['context'] == 'user' ) {
	// PUT /137.json/self		→	/backend.php?id=137&context=user
	// DELETE /137.json/self	→	/backend.php?id=137&context=user
	if ( $_SERVER['REQUEST_METHOD'] == 'PUT' ) {
		// Set the user's status to ready
		read_and_update_data($event_file, function($data) use($user) {
			$data['users'][$user] = true;
			return $data;
		});
	} elseif ( $_SERVER['REQUEST_METHOD'] == 'DELETE' ) {
		// Remove the user from the user list
		read_and_update_data($event_file, function($data) use($user) {
			unset($data['users'][$user]);
			return $data;
		});
	}
} else {
	// PUT /137.json		→	/backend.php?id=137
	// DELETE /137.json	→	/backend.php?id=137
	// POST /137.json		→	/backend.php?id=137
	if ( $_SERVER['REQUEST_METHOD'] == 'PUT' ) {
		// Update the title of the event
		parse_str(file_get_contents('php://input'), $args);
		var_dump($args['title']);
		if ( isset($args['title']) ){
			read_and_update_data($event_file, function($data) use($user, $args) {
				$data['title'] = $args['title'];
				return $data;
			});
			header('Content-Type: application/json', true, 204);
			exit();
		}
		header('Content-Type: application/json', true, 411);
		exit();
	} elseif ( $_SERVER['REQUEST_METHOD'] == 'DELETE' ) {
		// Delete the event completely
		if ( unlink($event_file) ) {
			header('Content-Type: application/json', true, 204);
			exit();
		} else {
			header('Content-Type: application/json', true, 500);
			exit();
		}
	} elseif ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		// Introduce a user (add him to the user list)
		read_and_update_data($event_file, function($data) use($user) {
			$data['users'][$user] = false;
			return $data;
		});
		header('Content-Type: application/json', true, 204);
		exit();
	}
}

?>