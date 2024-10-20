<?php
 $__headContent = '<link rel="stylesheet" href="/public/css/profile.css">';
?>

<section class="profile-section">
    <section class="profile-header">
        <h1 class="profile-title">Company Profile</h1>
        
    </section>

    <section class="profile-content">
        <div class="profile-info">
            <div class="profile-picture-profile">
                <img src="/public/svg/company.svg" alt="Company Logo" class="profile-pic-profile">
            </div>

            <div class="profile-details">
                <h2 class="profile-name"><?php echo $data['nama']; ?></h2>
                <p class="profile-email"><?php echo $data['email']; ?></p>
                <p class="profile-lokasi"><?php echo $data['lokasi']; ?></p>
            </div>
        </div>

        <div class="profile-about">
            <h2 class="profile-subtitle">About</h2>
            <p class="profile-description"><?php echo $data['about']; ?></p>
        </div>

        <a href="/edit-profile" class="edit-profile">
            
            <button class="button-profile">Edit Profile</button>
        </a>
    </section>
</section>