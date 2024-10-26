<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="LinkInPurry is a platform for job seekers and companies to connect.">
    <meta name="keywords" content="job, job seeker, company, recruitment, career">

    <!-- Favicon -->
    <link rel="icon" href="/public/ico/favicon.ico" type="image/x-icon">


    <!-- Source Sans Font (used by linkedin) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/public/css/global.css">
    <link rel="stylesheet" href="/public/css/header.css">
    <link rel="stylesheet" href="/public/css/toast.css">

    <!-- Quill.js -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <link href="/public/css/richtextarea.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <script src="/public/js/toast.js"></script>

    <?= $__headContent ?>
    <title>LinkInPurry</title>
</head>

<body class='text'>

    <?php
    include 'header.php';
    ?>

    <main>
        <div class="content">
            <?= $__content ?>
        </div>
    </main>

    <div id="customAlert" class="custom-alert">
        <p class="alert-message"></p>
        <button onclick="closeCustomAlert()">OK</button>
    </div>

    <?php
    $toastData = \app\helpers\Toast::get();
    if ($toastData) {
        echo '<script>showToast(' . json_encode($toastData) . ')</script>';
    }
    
    if (!empty($data) && (isset($data['success']) || isset($data['error']) || isset($data['warning']) || isset($data['help']))) {
        echo '<script>showToast(' . json_encode($data) . ')</script>';
    }
    ?>
</body>

<script>
    window.onload = function() {
        <?php if (isset($alert)): ?>
            showCustomAlert('<?php echo $alert; ?>');
            <?php unset($alert); ?>
        <?php endif; ?>
    };

    function showCustomAlert(message) {var customAlert = document.getElementById("customAlert");
        customAlert.querySelector(".alert-message").innerText = message;
        customAlert.style.display = "block";
    }

    function closeCustomAlert() {
        document.getElementById("customAlert").style.display = "none";
    }
</script>

</html>