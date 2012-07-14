<?
session_start();
header("Cache-control: private");

$_SESSION['isLogged'] = false;
session_destroy();

header('Location: index.php');
?>