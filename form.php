<link rel="stylesheet" type="text/css" href="style.css">

<?php
    session_start();
    
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

    if(mysqli_query($conn, $query)){
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
	
	echo '<div class="noti">
			<h4 id="paragh">This card was sent to your email address</h4>
		</div>';
    $_subject = 'Carte';
    $_header = 'From: mimi@gmail.com';
    //mail($email, $_subject, $_content, $_header);

?>


