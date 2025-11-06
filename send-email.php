<?php
// Prevent direct access
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 403 Forbidden');
    exit('Direct access not allowed');
}

// Set response header
header('Content-Type: application/json');

// Business owner's email
$to_email = 'hrwoolfrey@gmail.com';

// Get form data and sanitize
$name = isset($_POST['name']) ? htmlspecialchars(strip_tags(trim($_POST['name']))) : '';
$email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
$phone = isset($_POST['phone']) ? htmlspecialchars(strip_tags(trim($_POST['phone']))) : '';
$service = isset($_POST['service']) ? htmlspecialchars(strip_tags(trim($_POST['service']))) : '';
$message = isset($_POST['message']) ? htmlspecialchars(strip_tags(trim($_POST['message']))) : '';

// Validate required fields
if (empty($name) || empty($email) || empty($phone) || empty($service)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please fill in all required fields.'
    ]);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please enter a valid email address.'
    ]);
    exit;
}

// Convert service value to readable text
$service_names = [
    'lawn-maintenance' => 'Lawn Maintenance',
    'seasonal-cleanup' => 'Seasonal Clean-Up',
    'planting-mulching' => 'Planting & Mulching',
    'trash-removal' => 'Trash Removal',
    'other' => 'Other / Multiple Services'
];
$service_text = isset($service_names[$service]) ? $service_names[$service] : $service;

// Email subject
$subject = 'New Free Estimate Request from ' . $name;

// Email body
$email_body = "You have received a new free estimate request from your website.\n\n";
$email_body .= "Contact Details:\n";
$email_body .= "Name: $name\n";
$email_body .= "Email: $email\n";
$email_body .= "Phone: $phone\n";
$email_body .= "Service Interested In: $service_text\n\n";
$email_body .= "Message:\n$message\n\n";
$email_body .= "---\n";
$email_body .= "This email was sent from the H.W Landscaping website contact form.\n";

// Email headers
$headers = "From: H.W Landscaping Website <noreply@hwlandscaping.com>\r\n";
$headers .= "Reply-To: $name <$email>\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send email
if (mail($to_email, $subject, $email_body, $headers)) {
    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your request has been sent successfully.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Sorry, there was an error sending your message. Please call us directly at (508) 380-5563.'
    ]);
}
?>
