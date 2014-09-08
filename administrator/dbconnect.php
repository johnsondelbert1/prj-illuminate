<?php
$account = simplexml_load_file(dirname(__FILE__).'/../includes/backupaccount.xml');


session_name("setup");
session_start();

if(!isset($_SESSION['username'])){
	header("Location: setuplogin.php");
}
	function handleError($errno, $errstr, $errfile, $errline, array $errcontext)
	{
		// error was suppressed with the @-operator
		if (0 === error_reporting()) {
			return false;
		}
	
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}
set_error_handler('handleError');

if((isset($_POST['dbserver'])&&$_POST['dbserver']=="")||(isset($_POST['dbuser'])&&$_POST['dbuser']=="")||(isset($_POST['dbname'])&&$_POST['dbname']=="")){
	$message="<h3 class=\"error\">Server, User, or DB Name is blank.</h3>";
}else{
	if(isset($_POST['submit'])){
		try{
			mysqli_connect($_POST['dbserver'],  $_POST['dbuser'],  $_POST['dbpass'], $_POST['dbname']);
			
			$doc = new DOMDocument();
			$doc->formatOutput = true;
			
			$b = $doc->createElement( "database" );
			
				$firstrun = $doc->createElement( "firstrun" );
				$firstrun->appendChild(
					$doc->createTextNode( "false" )
				);
				$b->appendChild( $firstrun );
				
				$server = $doc->createElement( "server" );
				$server->appendChild(
					$doc->createTextNode( $_POST['dbserver'] ) 
				);
				$b->appendChild( $server );
				
				$username = $doc->createElement( "username" );
				$username->appendChild(
					$doc->createTextNode( $_POST['dbuser'] )
				);
				$b->appendChild( $username );
				
				$password = $doc->createElement( "password" );
				$password->appendChild(
					$doc->createTextNode( $_POST['dbpass'] )
				);
				$b->appendChild( $password );
				
				$name = $doc->createElement( "name" );
				$name->appendChild(
					$doc->createTextNode( $_POST['dbname'] )
				);
				$b->appendChild( $name );
			
			$doc->appendChild( $b );
			
			$doc->save(dirname(__FILE__).'/../includes/database.xml');
			
			$_SESSION=array();
			
			if(isset($_COOKIE[session_name()])){
				unset($_SESSION['username']);
				unset($_SESSION['password']);
			}
			
			session_destroy();
			
			header("Location: index.php");
			
		}catch (ErrorException $e){
			$message="<h3 class=\"error\">This information didn't work. Edit connection information here. \" ".$e->getMessage()." \"</h3>";
		}
	}
	if(isset($_GET['error'])&&$_GET['error']=="1"){
		
		//$message="<h3 class=\"error\">There is a problem connecting to the website database. Edit connection information here. Or is your SQL server down?</h2>";
	}
}

$dbconnect = simplexml_load_file(dirname(__FILE__).'/../includes/database.xml');

if(!isset($_POST['submit'])){
	
	if($dbconnect->server==""||$dbconnect->username==""||$dbconnect->name==""){
		if($dbconnect->firstrun!="true"){
			$message="<h3 class=\"error\">There is a problem connecting to the website database. Edit connection information here.</h3>";
		}
		
	}else{
		try{
			mysqli_connect($dbconnect->server,  $dbconnect->username,  $dbconnect->password, $dbconnect->name);
			
			$_SESSION=array();
			
			if(isset($_COOKIE[session_name()])){
				unset($_SESSION['username']);
				unset($_SESSION['password']);
			}
			
			session_destroy();
			
			header("Location: index.php");
		}catch (ErrorException $e){
			$message="<h3 class=\"error\">There is a problem connecting to the website database. Edit connection information here. Or is your SQL server down? \" ".$e->getMessage()." \"</h3>";
		}
	}
}
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Database Setup</title>
    <link rel="shortcut icon" href="images/favicon.png">
    <link href="styles/main.css" rel="stylesheet" type="text/css" />
    <link href="styles/fonts.css" rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript"></script>
    
    <style type="text/css">
		body{
			background-color:#0E4700;
			color:#FFFFFF;
		}
	</style>
</head>

<body>
<?php if(!empty($message)){echo $message;} ?>
<?php if($dbconnect->firstrun=="true"){echo "<h1>Step 1 of 2: Connect to Database</h1>";} ?>
<a href="setuplogin.php?action=logout">Logout</a><br>
<form method="post" action="dbconnect.php">
	Server <input type="text" name="dbserver" value="<?php echo $dbconnect->server; ?>" /><br>
	User <input type="text" name="dbuser" value="<?php echo $dbconnect->username; ?>" /><br>
	Pass <input type="text" name="dbpass" value="<?php echo $dbconnect->password; ?>" /><br>
	DB Name <input type="text" name="dbname" value="<?php echo $dbconnect->name; ?>" /><br>
    <input type="submit" name="submit" value="Submit DB" />
</form>
</body>
</html>