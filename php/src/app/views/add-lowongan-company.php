<?php
    $__headContent = '<link rel="stylesheet" href="/public/css/addLowongan.css">
    <script src="/public/js/richText.js" defer></script>';
    ?>

<section class="add-lowongan-section">
    <div class="add-lowongan-container">

        <h1>Add Vacancy</h1>
        <form id="lowonganForm" method="POST">
            <div class="form-group">
                <label for="vacancy-name">Vacancy Name</label>
                <input type="text" id="vacancy-name" name="vacancy-name">
            </div>
            <div class="form-group-row">
                <label for="lokasi">Lokasi</label>
                <select id="lokasi" name="lokasi">
                    <option value="hybrid">Hybrid</option>
                    <option value="on-site">On-Site</option>
                    <option value="remote">Remote</option>
                </select>
            </div>
            <div class="form-group-row">
                <label for="type">Type</label>
                <select id="type" name="type">
                    <option value="intern">Intern</option>
                    <option value="fulltime">Full Time</option>
                    <option value="part-time">Part Time</option>
                </select>
            </div>
            <div class="form-group-row">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="open">Open</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
            <div class="form-group">
                <label for="job-description">Job Description</label>
                    <div id="editor-container">
                        <div id="editor"></div>
                    </div>
                    <textarea id="quillTextArea" name="deskripsi" style="display:none"></textarea>
            </div>
            <!-- TODO: Add media upload -->
            <div class="form-group">
                <label for="media">Media</label>\
                <div class="media-upload">
                    <span>+</span>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn add">Add</button>
                <button type="button" class="btn discard">Discard</button>
            </div>
        </form>
    </div>
</section>


