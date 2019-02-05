<link rel="stylesheet" type="text/css" href="style.css">

<?php
    session_start();
    
    // import libraries
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/Exception.php';
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';

    // Configuration database
    $servername = "localhost";
    $username = "root"; 
    $password = "";
    $database = "form_contact";
    
    // Connection
    $conn = new mysqli($servername, $username, $password, $database);
        
    // Verify connection
    if ($conn->connect_error) 
	{
        echo "Connection failed: " . $conn->connect_error;
    }
        
    //init variables
    $firstname = $lastname = $email = $tel = "";
	
	// Clean the data before storing in the database
    function cleanData($data)
	{
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Retrieve data from the form
    if($_SERVER["REQUEST_METHOD"] == "POST")
	{
        $firstname 	= ucwords(cleanData($_POST['firstname']));
        $lastname 	= ucwords(cleanData($_POST['lastname']));
        $email 		= cleanData($_POST['email']);
        $tel 		= cleanData($_POST['tel']);	
    }    

    // Insert new data to the database
    $query = "INSERT INTO list_contact (firstname, lastname, email,tel) VALUES ('$firstname','$lastname', '$email', '$tel')";

    // Verify the transaction
    if(mysqli_query($conn, $query))
	{
        echo '<div class="noti">
			<h4 id="paragh">Data inserted successfully in database</h4>
		</div>';
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // UI to display
    $_content = '<div id="card">
        <p class="info">' . $firstname . ' </p> <br>
		<p class="info">' . $lastname .'</p> <br/>
        <p class="info">'.$email .'</p> <br/>
        <p class="info">'.$tel.'</p> <br/>
    </div>';
    // echo $_content;

    // Create image
    $image = imagecreatefromjpeg('card.jpg');

    // get the image size
    $w = imagesx($image);
    $h = imagesy($image);

    // Set color
    $red = imagecolorallocate($image, 255, 0, 0);
    
    $font = dirname(__FILE__) . '/font/roboto.ttf';

    // place some text (top, left)
    imagettftext($image, 20, 0, 0.2*$w, 0.3*$h, $red, $font, $firstname . ' ' . $lastname);
    imagettftext($image, 20, 0, 0.2*$w, 0.5*$h, $red, $font, $email);
    imagettftext($image, 20, 0, 0.2*$w, 0.7*$h, $red, $font, 'Tel: ' . $tel);

    // Output the image
    imagejpeg($image, 'card_001.jpg', 75);

    // Destroy
    imagedestroy($image);
    
    echo '<img src="card_001.jpg" alt="card"/>';

    // Mail sending
    $mail = new PHPMailer(true);
    try{
        //Server settings
        $mail->isSMTP();                                        // Set mailer to use SMTP
        $mail->Port = 587;                                      // TCP port to connect to

        //Recipients
        $mail->setFrom('mimo@gmail.com', 'MIMO');
        $mail->addAddress($email, $firstname);                  // Add a recipient

        //Attachments
        $mail->addAttachment('card_001.jpg');                // Add attachments
        
        //Content
        $mail->isHTML(true);                                    // Set email format to HTML
        $mail->Subject = 'Carte personnel';
        $mail->Body    = $_content;
        $mail->AltBody = $_content;
        
        $mail->send();
        echo '<div class="noti"><h4 id="paragh">This card was sent to your email address</h4></div>';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
?>