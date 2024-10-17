<?php
$__headContent = '<link rel="stylesheet" href="/public/css/profile.css">
<script src="/public/js/profile-edit-jobseeker.js" defer></script>';
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

    <div class="edit-profile">
        <button class="button-profile" id="editProfileBtn">Edit Profile</button>
    </div>
</section>

<!-- Overlay Modal -->
<div id="editProfileModal" class="overlay-modal hidden">
    <div class="modal-content">
        <span class="close-btn" id="closeModal">&times;</span>
        <h2>Edit Profile</h2>

        <form class="edit-form" id="editForm">
            <label for="nama">Your Name</label>
            <input type="text" id="nama" name="nama" value="<?php echo $data['nama']; ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $data['email']; ?>" required>

            <button type="submit" class="save-button">Save Changes</button>
        </form>
    </div>
</div>