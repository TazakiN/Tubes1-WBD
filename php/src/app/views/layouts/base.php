<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="LinkInPurry is a platform for job seekers and companies to connect.">
    <meta name="keywords" content="job, job seeker, company, recruitment, career">

    <!-- Source Sans Font (used by linkedin) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/public/css/global.css">
    <link rel="stylesheet" href="/public/css/header.css">

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

</body>

</html>