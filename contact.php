<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "cont"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Prepare and bind SQL statement
    $sql = "INSERT INTO contacts (name, email, phone, message) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $phone, $message);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Redirect back to the form with a success message
        header("Location: contact.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link rel="stylesheet" href="styleC.css">
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <span class="big-circle"></span>
        <img src="img/shape.png" class="square" alt="">
        <div class="form">
            <div class="contact-info">
                <h3 class="title">Let's get in touch</h3>
                <p class="text">To get in touch with us, please fill out the form below.<br> We will get back to you as soon as possible.</p>
                <div class="info">
                    <div class="information">
                        <img src="images/location.jpeg" class="icon" alt="">
                        <p>Gandhi Market, Margao, Goa</p>
                    </div>
                    <div class="information">
                        <img src="images/email.jpeg" class="icon" alt="">
                        <p>Wj1Kz@example.com</p>
                    </div>
                    <div class="information">
                        <img src="images/phone.jpeg" class="icon" alt="">
                        <p>+91 1234567890</p>
                    </div>
                </div>
            </div>

            <div class="contact-form">
                <span class="circle one"></span>
                <span class="circle two"></span>
                
                <form id="contactForm" action="contact.php" method="POST" autocomplete="off">
                    <h3 class="title">Contact us</h3>
                    <div class="input-container">
                        <input type="text" name="name" class="input" required placeholder="Username"/>
                        <span>Username</span>
                    </div>
                    <div class="input-container">
                        <input type="email" name="email" class="input" required placeholder="Email"/>
                        <span>Email</span>
                    </div>
                    <div class="input-container">
                        <input type="tel" name="phone" class="input" required placeholder="Phone"/>
                        <span>Phone</span>
                    </div>
                    <div class="input-container textarea">
                        <textarea name="message" class="input" required placeholder="Message"></textarea>
                    </div>
                    <input type="submit" value="Send" class="btn" />
                </form>
                
                <?php if (isset($_GET['success'])): ?>
                    <p id="confirmationMessage" style="color: green;">Message sent successfully!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
