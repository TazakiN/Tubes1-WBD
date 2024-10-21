<?php $__headContent = '
    <link rel="stylesheet" href="/public/css/edit-profile.css">
    <script src="/public/js/richText.js" defer></script>'; ?>

<section class="edit-profile-section">
    <div class="edit-profile-container">
        <h1>Edit Profile</h1>
        <span class="error-msg"><?php if (isset($errorMsg)) {
                                    echo "$errorMsg";
                                } ?></span>
        <form action="/edit-profile" method="POST" id="updateForm">
            <label for="nama">Name</label>
            <input type="text" id="nama" name="nama" value="<?php echo $data["nama"] ?>">
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $data["email"] ?>">

            <label for="lokasi">Lokasi</label>
            <input type="text" id="lokasi" name="lokasi" value="<?php echo $data["lokasi"] ?>">

            <div id="editor-container">
                <label for="about" class="input-label">Tentang Perusahaan</label><br>
                <div id="editor"><?php echo $data["about"] ?></div>
                <textarea id="quillTextArea" name="about" style="display:none"></textarea>
            </div>
            
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