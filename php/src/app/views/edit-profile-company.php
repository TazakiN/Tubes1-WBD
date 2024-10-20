<?php $__headContent = '<link rel="stylesheet" href="/public/css/edit-profile.css">'; ?>

<section class="edit-profile-section">
    <div class="edit-profile-container">
        <h1>Edit Profile</h1>
        <span class="error-msg"><?php if (isset($errorMsg)) {
                                    echo "$errorMsg";
                                } ?></span>
        <form action="/edit-profile" method="POST">
            <label for="nama">Name</label>
            <input type="text" id="nama" name="nama" value="<?php echo $data["nama"] ?>">
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $data["email"] ?>">

            <label for="lokasi">Lokasi</label>
            <input type="text" id="lokasi" name="lokasi" value="<?php echo $data["lokasi"] ?>">

            <label for="about">About</label>
            <textarea id="about" name="about"><?php echo $data["about"] ?></textarea>
            
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" placeholder="(leave blank if not going to change)">

            <label for="confirm-password">Confirm New Password</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="(leave blank if not going to change)">
            
            <div class="buttons">
                <button type="submit" class="save">Save</button>
                <button type="button" class="discard">Discard</button>
            </div>
        </form>
    </div>
</section>