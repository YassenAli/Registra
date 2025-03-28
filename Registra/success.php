<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUCCESS! - Registra</title> <?php  ?>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="dark-theme">
    <?php include 'header.php'; ?>
    <main class="form-page-container dark-theme awesome-success-bg">
        <?php  ?>
        <div class="success-wrapper awesome-success" id="successCard">
            <div class="success-graphic-elements"> <?php  ?>
                <span class="graphic-shape shape-1"></span>
                <span class="graphic-shape shape-2"></span>
            </div>
            <div class="success-icon-container">
                <i class="fas fa-check-circle success-icon"></i>
            </div>
            <h2>Awesome! You're Registered!</h2>
            <p>Your spot is secured. Welcome to the Registra community!</p>
            <div class="success-actions">
                <a href="index.php" class="button-awesome-link">
                    <span>Explore Now</span> <?php  ?>
                    <i class="fas fa-rocket"></i> <?php  ?>
                </a>
            </div>
            <span class="graphic-shape shape-3"></span>
            <span class="graphic-shape shape-4"></span>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>

</html>