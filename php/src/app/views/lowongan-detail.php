        <div class="header">
            <img src="/public/svg/company.svg" alt="Company Logo">
            <h1><?php echo $data["nama"] ?></h1>
        </div>
        <?php
            if ($data["is_open"]) {
                echo '<div class="status open" id="status">Open</div>';
            } else {
                echo '<div class="status closed" id="status">Closed</div>';
            }
        ?>
        
        <h1 class="job-title"><?php echo $data["posisi"] ?></h1>
        <div class="job-info">
            <span class="job-type">
                <img src="/public/svg/location.svg" alt="Location SVG">    
                <?php echo $data["jenis_lokasi"] ?>
            </span>
            <span class="job-category">
                <img src="/public/svg/job-case.svg" alt="Job Category SVG">    
                <?php echo $data["jenis_pekerjaan"] ?>
            </span>
            <span class="job-date">
                <img src="/public/svg/calendar.svg" alt="Job Category SVG">
                Posted: <?php echo $data["created_at"]; ?> | 
                Updated: <?php echo $data["updated_at"]; ?>
            </span>
        </div>
        
        <div class="description">
            <?php echo $data["deskripsi"] ?>
        </div>
        
        <div class="attachments">
            <h2>Attachments</h2>
            <div class="images">
                <?php foreach ($data['attachments'] as $attachment): ?>
                    <div class="image-container">
                        <!-- Menampilkan gambar -->
                        <img src="/uploads/<?php echo htmlspecialchars($attachment->file_path); ?>" 
                            alt="Attachment Image" class="attachment-image" />

                        <!-- Menampilkan nama file -->
                        <p class="file-name"><?php echo htmlspecialchars(string: explode("_", $attachment->get('file_path'))[1]); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>