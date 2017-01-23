<?php
include("../includes/functions.php");
if(isset($args[1])&&isset($args[2])&&isset($args[3])){
	$subType = $args[1];
	$subId = $args[2];
	$poster = $args[3];

	$mailList = array();

	$query = "SELECT * FROM `users`";
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	while ($userData = mysqli_fetch_array($result)) {
		$subscriptions = unserialize($userData['subscriptions']);
		if(in_array($subId, $subscriptions[$subType])&&$userData['id']!=$poster){
			array_push($mailList, $userData['email']);
		}
	}

	if(count($mailList) > 0){
		//$to = implode(',', $mailList);

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: no-reply@".$_SERVER['HTTP_HOST'].PHP_EOL;

		switch ($subType) {
			case 'blog':
				$subject = 'New Blog Post at '.$site_info['name'];
				$email_message = 'A new post has been made in the blog!<br />';
				$email_message .= 'View the post here: <a href="'.$GLOBALS['HOST'].'/page/'.$GLOBALS['blog_page'].'">'.$GLOBALS['HOST'].'/page/'.$GLOBALS['blog_page'].'</a>';
				break;
			
			case 'forum':
				$subject = 'New Forum Post at '.$site_info['name'];
				$email_message = 'A new post has been made in a forum you\'ve subscribed!<br />';
				$email_message .= 'View the post here: <a href="'.$GLOBALS['HOST'].'/view_forum?forum='.$subId.'">'.$GLOBALS['HOST'].'/view_forum?forum='.$subId.'</a>';
				break;
			
			case 'thread':
				$subject = 'New Thread Post at '.$site_info['name'];
				$email_message = 'A new post has been made in a forum thread you\'ve subscribed to!<br />';
				$email_message .= 'View the post here: <a href="'.$GLOBALS['HOST'].'/view_thread?thread='.$subId.'">'.$GLOBALS['HOST'].'/view_thread?thread='.$subId.'</a>';
				break;

			default:
				//nothing
				break;
		}
		foreach ($mailList as $email) {
			mail($email, $subject, $email_message, $headers);
		}
	}
}
?>