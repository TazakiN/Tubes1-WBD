<?php
 $__headContent = '<link rel="stylesheet" href="/public/css/profile.css">
 <script src="/public/js/profile-edit.js" defer></script>';
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
                <p class="profile-location"><?php echo $data['lokasi']; ?></p>
            </div>
        </div>

        <div class="profile-about">
            <h2 class="profile-subtitle">About</h2>
            <p class="profile-description"><?php echo $data['about']; ?></p>
        </div>

        <div class="edit-profile">
            <button class="button-profile" id="editProfileBtn">Edit Profile</button>
        </div>
    </section>
</section>

<!-- Overlay Modal -->
<div id="editProfileModal" class="overlay-modal hidden">
    <div class="modal-content">
        <span class="close-btn" id="closeModal">&times;</span>
        <h2>Edit Profile</h2>

        <form action="/update-profile" method="POST" class="edit-form">
            <label for="name">Company Name</label>
            <input type="text" id="name" name="name" value="<?php echo $data['nama']; ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $data['email']; ?>" required>

            <label for="location">Location</label>
            <input type="text" id="location" name="location" value="<?php echo $data['lokasi']; ?>" required>

            <label for="about">About</label>
            <textarea id="about" name="about" rows="4" required><?php echo $data['about']; ?></textarea>

            <button type="submit" class="save-button">Save Changes</button>
        </form>
    </div>
</div>