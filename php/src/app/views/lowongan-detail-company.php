<?php
    $__headContent = '<link rel="stylesheet" href="/public/css/lowongan-detail.css">';
?>

<section class="lowongan-detail-section">
    <div class="container">
        <?php
            include "lowongan-detail.php";
        ?>

        <button class="action-button">Edit</button>
        
        <!-- TODO: Masih Placeholder -->
        <div class="applicants">
            <h2>Applicants</h2>
            <div class="applicant">
                <span class="applicant-name">Tazkia Ganteng Banget</span>
                <div class="applicant-status">
                    <span class="status waiting">Waiting</span>
                    <button class="details-button">Details</button>
                </div>
            </div>
            <div class="applicant">
                <span class="applicant-name">Farhan Seksi Banget</span>
                <div class="applicant-status">
                    <span class="status waiting">Waiting</span>
                    <button class="details-button">Details</button>
                </div>
            </div>
        </div>
    </div>
</section>
    