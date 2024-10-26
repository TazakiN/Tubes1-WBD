<?php
    $__headContent = '<link rel="stylesheet" href="/public/css/lowongan-detail.css">';
?>

<section class="lowongan-detail-section">
    <div class="container">
        <?php
        include "lowongan-detail.php";
        ?>
        
        <div class="application-status-container">
            <?php if (!$data['is_melamar']) { ?>
                <a href="/lamaran/add?lowongan_id=<?php echo $data['lowongan_id']; ?>">
                    <button class="action-button" id="lamarButton">Apply</button>
                </a>
            <?php } else { ?>
                <div class="application-details">
                    <h3>Applications Detail</h3>
                    
                    <div class="documents-section">
                        <?php if ($data['lamaran']['cv_path']) { ?>
                            <a href="<?php echo $data['lamaran']['cv_path']; ?>" 
                               target="_blank" 
                               class="document-link cv-link">
                                <svg class="icon" viewBox="0 0 24 24" width="24" height="24">
                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M9,13V19H7V13H9M15,13V19H17V13H15M11,13V19H13V13H11Z" 
                                          fill="currentColor"/>
                                </svg>
                                Lihat CV
                            </a>
                        <?php } ?>
                        
                        <?php if ($data['lamaran']['video_path']) { ?>
                            <a href="<?php echo $data['lamaran']['video_path']; ?>" 
                               target="_blank" 
                               class="document-link video-link">
                                <svg class="icon" viewBox="0 0 24 24" width="24" height="24">
                                    <path d="M17,10.5V7A1,1 0 0,0 16,6H4A1,1 0 0,0 3,7V17A1,1 0 0,0 4,18H16A1,1 0 0,0 17,17V13.5L21,17.5V6.5L17,10.5Z" 
                                          fill="currentColor"/>
                                </svg>
                                Lihat Video
                            </a>
                        <?php } ?>
                    </div>
                    <div class="status-section">
                        <p class="status-label">Application Status:</p>
                        <div class="status-badge <?php echo strtolower($data['lamaran']['status']); ?>">
                            <?php if (strtolower($data['lamaran']['status']) === 'waiting') { ?>
                                <svg class="icon" viewBox="0 0 24 24" width="16" height="16">
                                    <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M12.5,7H11V13L16.2,16.2L17,14.9L12.5,12.2V7Z" 
                                          fill="currentColor"/>
                                </svg>
                            <?php } elseif (strtolower($data['lamaran']['status']) === 'accepted') { ?>
                                <svg class="icon" viewBox="0 0 24 24" width="16" height="16">
                                    <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M11,16.5L6.5,12L7.91,10.59L11,13.67L16.59,8.09L18,9.5L11,16.5Z"
                                          fill="currentColor"/>
                                </svg>
                            <?php } elseif (strtolower($data['lamaran']['status']) === 'rejected') { ?>
                                <svg class="icon" viewBox="0 0 24 24" width="16" height="16">
                                    <path d="M12,2C17.53,2 22,6.47 22,12C22,17.53 17.53,22 12,22C6.47,22 2,17.53 2,12C2,6.47 6.47,2 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M15.59,7L17,8.41L13.41,12L17,15.59L15.59,17L12,13.41L8.41,17L7,15.59L10.59,12L7,8.41L8.41,7L12,10.59L15.59,7Z"
                                          fill="currentColor"/>
                                </svg>
                            <?php } ?>
                            <?php echo ucfirst($data['lamaran']['status']); ?>
                        </div>
                        
                        <?php if ($data['lamaran']['status_reason']) { ?>
                            <p class="status-reason">
                                <?php echo $data['lamaran']['status_reason']; ?>
                            </p>
                        <?php } ?>
                    </div>

                    <div class="application-note">
                        <p class="note-label">Applicants Notes:</p>
                        <p class="note-content"><?php echo $data['lamaran']['note']; ?></p>
                    </div>

                    <div class="application-date">
                        <svg class="icon" viewBox="0 0 24 24" width="16" height="16">
                            <path d="M12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22C6.47,22 2,17.5 2,12A10,10 0 0,1 12,2M12.5,7V12.25L17,14.92L16.25,16.15L11,13V7H12.5Z" 
                                  fill="currentColor"/>
                        </svg>
                        <small>Diajukan pada: <?php 
                            echo date('d F Y H:i', strtotime($data['lamaran']['created_at'])); 
                        ?></small>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>