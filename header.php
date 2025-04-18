<?php
// Start session for CSRF protection and messages
session_start();

// Generate CSRF token if not exists
// if (empty($_SESSION['csrf_token'])) {
//     $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Professional user registration system">
    <title>Registra</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .whatsapp-check {
    background-color: #25D366; /* WhatsApp brand color */
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    margin-top: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}

.whatsapp-check:hover {
    background-color: #1da851;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(37, 211, 102, 0.2);
}

.whatsapp-check:active {
    transform: translateY(0);
}

.whatsapp-check i {
    font-size: 1.2em;
}

/* Validation states */
.whatsapp-check.valid {
    background-color:rgba(40, 167, 70, 0.51);
    animation: pulseValid 0.5s ease;
}

.whatsapp-check.invalid {
    background-color:rgba(220, 53, 70, 0.69);
    animation: shake 0.5s ease;
}

@keyframes pulseValid {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

@keyframes shake {
    0% { transform: translateX(0); }
    25% { transform: translateX(5px); }
    50% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
    100% { transform: translateX(0); }
}

/* Input group styling */
.form-group {
    position: relative;
}

.form-group .form-control {
    padding-right: 100px; /* Make space for button */
}

#whatsAppFeedback {
    margin-top: 5px;
    font-size: 0.9em;
}

/* Mobile responsive */
@media (max-width: 576px) {
    .whatsapp-check {
        width: 100%;
        justify-content: center;
        margin-top: 10px;
    }
    
    .form-group .form-control {
        padding-right: 15px;
    }
}
    </style>
</head>
<body>
    <header class="main-header">
        <div class="container">
            <a href="index.php" class="logo">
                <img src="images\malak.jpg" alt="Registra Logo" class="logo-img">
            </a>
            
            <nav class="main-nav">
                <ul class="nav-list">
                    <li class="nav-item"><a href="index.php" class="nav-link active"><i class="fas fa-home"></i> Home</a></li>
                    <li class="nav-item"><a href="about.php" class="nav-link"><i class="fas fa-info-circle"></i> About</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link"><i class="fas fa-envelope"></i> Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="content-wrapper">
