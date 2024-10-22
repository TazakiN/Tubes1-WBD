<?php
    $__headContent = '<link rel="stylesheet" href="/public/css/addLowongan.css">
    <script src="/public/js/richText.js" defer></script>
    <script src="/public/js/addLowongan.js" defer></script>';
    ?>

<section class="add-lowongan-section">
    <div class="add-lowongan-container">

        <h1>Add Vacancy</h1>
        <form id="lowonganForm">
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
            <div class="form-group media-upload-section">
                <label for="media-upload">Media Upload</label>
                <div class="upload-container" id="uploadContainer">
                    <div class="upload-area" id="uploadArea">
                        <input type="file" id="fileInput" multiple accept="image/*,.pdf,.doc,.docx" style="display: none;">
                        <div class="upload-content">
                            <i class="upload-icon">📁</i>
                            <p>Drag and drop files here or <span class="browse-text">browse</span></p>
                            <p class="file-support">Supports: Images, PDF, DOC, DOCX</p>
                        </div>
                    </div>
                    <div class="file-preview-container" id="filePreviewContainer">
                        <!-- Preview items will be added here dynamically -->
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn add">Add</button>
                <button type="button" class="btn discard">Discard</button>
            </div>
        </form>
    </div>
</section>


