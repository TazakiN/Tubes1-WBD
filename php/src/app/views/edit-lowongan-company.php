<?php
    $__headContent = '<link rel="stylesheet" href="/public/css/addLowongan.css">
    <script src="/public/js/editLowongan.js" defer></script>
    <script src="/public/js/richText.js" defer></script>';
?>

<section class="add-lowongan-section">
    <div class="add-lowongan-container">
        <h1>Edit Vacancy</h1>
        <form id="editLowonganForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="lowongan_id" value="<?php echo htmlspecialchars($data['lowongan_id']); ?>">
            
            <div class="form-group">
                <label for="vacancy-name">Vacancy Name</label>
                <input type="text" id="vacancy-name" name="vacancy-name" 
                    value="<?php echo htmlspecialchars($data['posisi'], ENT_QUOTES); ?>">
            </div>

            <div class="form-group-row">
                <label for="lokasi">Lokasi</label>
                <select id="lokasi" name="lokasi">
                    <option value="Hybrid" <?php echo ($data["jenis_lokasi"] == "Hybrid") ? "selected" : ""; ?>>Hybrid</option>
                    <option value="On-site" <?php echo ($data["jenis_lokasi"] == "On-site") ? "selected" : ""; ?>>On-Site</option>
                    <option value="Remote" <?php echo ($data["jenis_lokasi"] == "Remote") ? "selected" : ""; ?>>Remote</option>
                </select>
            </div>

            <div class="form-group-row">
                <label for="type">Type</label>
                <select id="type" name="type">
                    <option value="Internship" <?php echo ($data["jenis_pekerjaan"] == "Internship") ? "selected" : ""; ?>>Intern</option>
                    <option value="Full-time" <?php echo ($data["jenis_pekerjaan"] == "Full-time") ? "selected" : ""; ?>>Full Time</option>
                    <option value="Part-time" <?php echo ($data["jenis_pekerjaan"] == "Part-time") ? "selected" : ""; ?>>Part Time</option>
                </select>
            </div>

            <div class="form-group-row">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="open" <?php echo ($data["is_open"]) ? "selected" : ""; ?>>Open</option>
                    <option value="closed" <?php echo (!$data["is_open"]) ? "selected" : ""; ?>>Closed</option>
                </select>
            </div>

            <div class="form-group">
                <label for="job-description">Job Description</label>
                <div id="editor-container">
                    <div id="editor"></div>
                </div>
                <textarea id="quillTextArea" name="deskripsi" style="display:none"><?php echo htmlspecialchars($data["deskripsi"]); ?></textarea>
            </div>

            <div class="form-group media-upload-section">
                <label for="media-upload">Media Upload</label>
                <div class="upload-container" id="uploadContainer">
                    <div class="upload-area" id="uploadArea">
                        <input type="file" id="fileInput" name="files[]" multiple 
                            accept="image/*" style="display: none;">
                        <div class="upload-content">
                            <i class="upload-icon">🖼️</i>
                            <p>Drag and drop files here or <span class="browse-text">browse</span></p>
                            <p class="file-support">Supports: JPEG, PNG, GIF</p>
                        </div>
                    </div>

                    <div class="file-preview-container" id="filePreviewContainer">
                        <?php if (!empty($data["attachments"])): ?>
                            <?php foreach ($data["attachments"] as $attachment): ?>
                                <div class="file-preview-item" data-file-id="<?php echo $attachment->attachment_id; ?>">
                                    <a href="/uploads/<?php echo htmlspecialchars($attachment->file_path); ?>" target="_blank">
                                        <?php
                                            $fileName = explode("_", $attachment->file_path)[1];
                                            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                            $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif']);
                                        ?>
                                        <?php if ($isImage): ?>
                                            <img src="/uploads/<?php echo htmlspecialchars($attachment->file_path); ?>" alt="<?php echo htmlspecialchars($fileName); ?>">
                                        <?php else: ?>
                                            <div class="file-icon">
                                                <?php
                                                    $icon = '📎';
                                                    if ($fileExt === 'pdf') $icon = '📄';
                                                    if (in_array($fileExt, ['doc', 'docx'])) $icon = '📝';
                                                    echo $icon;
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="file-name"><?php echo htmlspecialchars($fileName); ?></div>
                                    </a>
                                    <button type="button" class="remove-file" data-attachment-id="<?php echo $attachment->attachment_id; ?>">×</button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <input type="hidden" name="deleted_attachments" id="deletedAttachments" value="">

            <div class="form-actions">
                <button type="submit" class="btn add">Update</button>
                <button type="button" class="btn discard">Discard</button>
            </div>
        </form>
    </div>
</section>

<script>
    window.initialContent = <?php echo json_encode($data["deskripsi"]); ?>;
    window.existingAttachments = <?php echo json_encode(
    is_array($data["attachments"]) ? array_map(function ($att) {
        return [
            'id' => $att->attachment_id,
            'path' => $att->file_path,
            'name' => explode("_", $att->file_path)[1]
        ];
    }, $data["attachments"]) : []
); ?>;
</script>