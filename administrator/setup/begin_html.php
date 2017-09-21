<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $pgtitle; ?></title>
    <link rel="shortcut icon" href="images/favicon.png">
    <link href="../styles/main.css" rel="stylesheet" type="text/css" />
    <link href="../styles/fonts.css" rel="stylesheet" type="text/css" />
    <link href="../styles/materialize.css" rel="stylesheet" type="text/css" media="screen,projection"/>
    <link href="styles/materialize-override.css" rel="stylesheet" type="text/css" media="screen,projection"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript"></script>
    

</head>

<body>
<div class="container">
<?php if(!empty($message)){echo $message;} ?>
<h1><?php echo $pgtitle; ?></h1>
<div class="card-panel">