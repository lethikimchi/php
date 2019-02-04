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
		
			//Insérer les informations dans la table list_contact
			mysqli_query($conn, "INSERT INTO list_contact (firstname, lastname, email, tel)
					VALUES ('$firstname','$lastname', '$email', '$tel') ");
			
			/*
			$my_img = imagecreate( 200, 80 );
			$background = imagecolorallocate( $my_img, 0, 0, 255 );
			$text_colour = imagecolorallocate( $my_img, 255, 255, 0 );
			$line_colour = imagecolorallocate( $my_img, 128, 255, 0 );
			imagestring( $my_img, 4, 30, 25, $firstname, $text_colour );
			imagesetthickness ( $my_img, 5 );
			imageline( $my_img, 30, 45, 165, 45, $line_colour );

			header( "Content-type: image/png" );
			imagepng( $my_img );
			imagecolordeallocate( $line_color );
			imagecolordeallocate( $text_color );
			imagecolordeallocate( $background );
			imagedestroy( $my_img );
	
			*/
			include ('response.html');
			
		
		
		
	}
	
	
?>