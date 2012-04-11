<?php

$_CONFIG = array(
	'data_dir' => 'data'
);

function read_and_update_data($id, $handler){
	global $_CONFIG;
	$initialized_data = false;
	
	$fd = fopen($_CONFIG['data_dir'] . '/' . basename($id), 'c+');
	flock($fd, LOCK_EX);
	
	$data = json_ decode( stream_get_contents($fd), true );
	if ($data == null){
		$data = array('title' => null, 'users' => array());
		$initialized_data = true;
	}
	
	$updated_data = $handler($data);
	
	if ($updated_data or $initialized_data){
		ftruncate($fd, 0);
		fwrite($fd, json_encode($updated_data));
	}
	
	flock($fd, LOCK_UN);
	fclose($fd);
}

/**
 * Escapes the specified text so it can be safely inserted as HTML tag content.
 * It's UTF-8 safe.
 * 
 * Since this function is made for HTML content it does not escape double
 * quotes ("). If you want to insert something as an attribute value use the
 * ha() function.
 * 
 * This is a shortcut mimicing the Ruby on Rails "h" helper.
 */
function h($text_to_escape){
	return htmlspecialchars($text_to_escape, ENT_NOQUOTES, 'UTF-8');
}

/**
 * Escapes the specified text so it can be safely inserted into an HTML attribute.
 * It's UTF-8 safe.
 * 
 * This is a shortcut mimicing the Ruby on Rails "h" helper.
 */
function ha($text_to_escape){
	return htmlspecialchars($text_to_escape, ENT_QUOTES, 'UTF-8');
}

?>