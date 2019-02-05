<link rel="stylesheet" type="text/css" href="style.css">

<?php
    session_start();
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/Exception.php';
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';

    $servername = "localhost";
    $username = "root"; 
    $password = "";
    $database = "form_contact";
    
    $conn = new mysqli($servername, $username, $password, $database);
        
    // Check connection
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

    if($_SERVER["REQUEST_METHOD"] == "POST")
	{
        $firstname 	= ucwords(cleanData($_POST['firstname']));
        $lastname 	= ucwords(cleanData($_POST['lastname']));
        $email 		= cleanData($_POST['email']);
        $tel 		= cleanData($_POST['tel']);	
    }    

    $query = "INSERT INTO list_contact (firstname, lastname, email,tel) VALUES ('$firstname','$lastname', '$email', '$tel')";

    if(mysqli_query($conn, $query))
	{
        echo '<div class="noti">
			<h4 id="paragh">Data inserted successfully in database</h4>
		</div>';
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }


    $_content = '<div id="card">
        <p class="info">' . $firstname . ' </p> <br>
		<p class="info">' . $lastname .'</p> <br/>
        <p class="info">'.$email .'</p> <br/>
        <p class="info">'.$tel.'</p> <br/>
    </div>';
    echo $_content;
    
    // Convert html to image
    $data = json_decode(file_get_contents('http://api.rest7.com/v1/html_to_image.php?url=' . $_content . '&format=png'));

    if (@$data->success !== 1){
        echo 'Failed';
    }
    
    $image = file_get_contents($data->file);
    file_put_contents('images/card.png', $image);

    // Mail sending
    $mail = new PHPMailer(true);
    try{
        //Server settings
        $mail->SMTPDebug = 2;                                   // Enable verbose debug output
        $mail->isSMTP();                                        // Set mailer to use SMTP
        $mail->SMTPAuth = true;                                 // Enable SMTP authentication
        $mail->SMTPSecure = 'tls';                              // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                      // TCP port to connect to

        //Recipients
        $mail->setFrom('mimo@gmail.com', 'MIMO');
        $mail->addAddress($email, $firstname);                  // Add a recipient

        //Attachments
        $mail->addAttachment('images/card.png');                // Add attachments
        
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