<?php
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$email = $_POST['email'];
	$tel = $_POST['tel'];


	$host = 'localhost';
	$dbusername = 'root';
	$dbpassword = '';
	$dbname = 'form_contact';
	
	$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
	
	if ($conn->connect_errno) 
	{ // Vérification de la connexion
	echo "Connection failed: (" . $conn->connect_errno . ") " . $conn->connect_error;
	exit(); // interruption de l'exécution
	}
	
	else 
	{
		mysqli_query($conn, "INSERT INTO list_contact (firstname, lastname, email, tel)
					VALUES ('$firstname','$lastname', '$email', '$tel') ");
	}
	
	
?>