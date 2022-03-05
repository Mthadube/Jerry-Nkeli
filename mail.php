<?php
if(isset($_POST['g-recaptcha-response'])){
    $captcha=$_POST['g-recaptcha-response'];
}
if(!$captcha){
    echo 'Please check the reCAPTCHA.';
    exit;
}
$secretKey = "6LdxaPcbAAAAAL7GCjgnt2pXhwihICfpQJzoWy53";
$ip = $_SERVER['REMOTE_ADDR'];
        // post request to server
$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
$response = file_get_contents($url);
$responseKeys = json_decode($response,true);
        // should return JSON with success as true
if($responseKeys["success"]) {
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $mail_to = "info@anythingtech.co.za";
    
        # Sender Data
    $name = str_replace(array("\r","\n"),array(" "," ") , strip_tags(trim($_POST["name"])));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = trim($_POST["phone"]);
    $package = trim($_POST["package"]);
    $message = trim($_POST["comment"]);
    
    if ( empty($name) OR !filter_var($email, FILTER_VALIDATE_EMAIL) OR empty($phone) OR empty($message)) {
            # Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Please complete the form and try again.";
        exit;
    }
    
        # Mail Content
    $content = "Name: $name\n";    
    $content .= "Email: $email\n";
    $content .= "Phone: $phone\n";
    $content .= "Service: $package\n";
    $content .= "Message: $message\n";

        # email headers.
    $headers = "Enquiry Details from a user at www.anythingtech.co.za";

        # Send the email.
    $success = mail($mail_to, $subject, $content, $headers);
    if ($success) {
            # Set a 200 (okay) response code.
        http_response_code(200);
        echo "Thank You! Your message has been sent.";
    } else {
            # Set a 500 (internal server error) response code.
        http_response_code(500);
        echo "Oops! Something went wrong, we couldn't send your message.";
    }

} else {
        # Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}

?>
