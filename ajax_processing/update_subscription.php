<?php
include("../includes/functions.php");
if(logged_in()){
	if(isset($_POST['action'])&&isset($_POST['type'])&&isset($_POST['id'])){
		$subscriptions = unserialize($user_info['subscriptions']);
		if($_POST['action'] == 'sub'){
			//Add item to subscriptions array
			if(!in_array($_POST['id'], $subscriptions[$_POST['type']])){
				array_push($subscriptions[$_POST['type']], $_POST['id']);
			}
		}elseif($_POST['action'] == 'unsub'){
			//Remove item from subscriptions array and reindex
			if(($key = array_search($_POST['id'], $subscriptions[$_POST['type']])) !== false){
				unset($subscriptions[$_POST['type']][$key]);
				$subscriptions[$_POST['type']] = array_values($subscriptions[$_POST['type']]);
			}
		}
		//Reserialize and update the array in the DB
		$subscriptions = serialize($subscriptions);

		$query = "UPDATE `users` SET `subscriptions` = '{$subscriptions}' WHERE `id` = {$_SESSION['user_id']}";
		$result = mysqli_query($connection, $query);
		confirm_query($result);

		echo 'success';
	}else{
		echo 'Missing args';
	}
}else{
	echo 'Not Logged In';
}
?>