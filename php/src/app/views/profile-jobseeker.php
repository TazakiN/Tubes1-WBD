<?php
$__headContent = '<link rel="stylesheet" href="/public/css/profile.css">';
?>

<section class="profile-section">
    <div class="profile-header">
        <h1 class="profile-title">Job Seeker Profile</h1>
        <p class="error-msg"><?php if (isset($errorMsg)) {
                                    echo "$errorMsg";
                                } ?></p>
    </div>

    <div class="profile-content">
        <div class="profile-info">
            <div class="profile-picture">
                <img src="/public/svg/personHitam.svg" alt="Person Logo" class="profile-pic">
            </div>

            <div class="profile-details">
                <h2 class="profile-name" id="displayNama"><?php echo $data['nama']; ?></h2>
                <p class="profile-email" id="displayEmail"><?php echo $data['email']; ?></p>
            </div>
        </div>
    </div>

    <div class="profile-about">
    </div>

    <a class="edit-profile" href="/edit-profile">
        <button class="button-profile" >Edit Profile</button>
    </a>
</section>