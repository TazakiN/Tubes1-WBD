<?php
    $__headContent =
    '<link rel="stylesheet" href="/public/css/home-lowongan-jobseeker.css">
    <script src="/public/js/lowonganJobseeker.js" defer></script>';
?>
<section class="home-section">
    <div class="header">
        <div class="search-bar">
            <input type="text" class="search-input" placeholder="Search..." id="searchInput">
            <button class="filter-button">Filter</button>
            <div class="check-container">
                <label class="check-item"><input type="checkbox" name="location" value="on-site" checked>On-site</label>
                <label class="check-item"><input type="checkbox" name="location" value="hybrid" checked>Hybrid</label>
                <label class="check-item"><input type="checkbox" name="location" value="remote" checked>Remote</label>
            </div>

            <div class="check-container">
                <label class="check-item"><input type="checkbox" name="type" value="Internship" checked>Internship</label>
                <label class="check-item"><input type="checkbox" name="type" value="Part-time" checked>Part-time</label>
                <label class="check-item"><input type="checkbox" name="type" value="Full-time" checked>Full-time</label>
            </div>
        </div>
        <h1>Job Listing</h1>
    </div>

    <hr>
    <br>
    
    <div class="job-grid">
        <?php foreach($data["lowongans"] as $k => $v): ?>
            <div class="job-card" onclick="window.location.href='/lowongan?lowongan_id=<?= $v->lowongan_id ?>'">
                <div class="profile-icon">
                    <svg width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div class="job-info">
                    <h3 class="job-title"><?= htmlspecialchars($v->posisi) ?></h3>
                    <div class="company"><?= htmlspecialchars($v->company_name) ?></div>
                    <div class="tags">
                        <span class="tag"><?= ucfirst(htmlspecialchars($v->jenis_pekerjaan)) ?></span>
                        <span class="tag"><?= ucfirst(htmlspecialchars($v->jenis_lokasi)) ?></span>
                    </div>
                    <div class="date">
                        <img src="/public/svg/calendar.svg" alt="Calendar Logo">
                        <?= date('d-m-Y', strtotime($v->created_at)) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="pagination">
    <?php 
        $currentPage = $data['page'];
        $totalPages = $data['totalPage'];

        if ($currentPage > 1): ?>
            <a href="/home?page=<?= $currentPage - 1 ?>" class="pagination-btn">&larr;</a>
        <?php else: ?>
            <a href="#" class="pagination-btn disabled">&larr;</a>
        <?php endif;

        if ($currentPage > 3): ?>
            <a href="/home?page=1" class="pagination-btn">1</a>
            <?php if ($currentPage > 4): ?>
                <span class="pagination-dots">...</span>
            <?php endif;
        endif;

        for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
            <a href="/home?page=<?= $i ?>" 
               class="pagination-btn <?= $currentPage === $i ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor;

        if ($currentPage < $totalPages - 2): ?>
            <?php if ($currentPage < $totalPages - 3): ?>
                <span class="pagination-dots">...</span>
            <?php endif; ?>
            <a href="/home?page=<?= $totalPages ?>" class="pagination-btn">
                <?= $totalPages ?>
            </a>
        <?php endif;

        if ($currentPage < $totalPages): ?>
            <a href="/home?page=<?= $currentPage + 1 ?>" class="pagination-btn">&rarr;</a>
        <?php else: ?>
            <a href="#" class="pagination-btn disabled">&rarr;</a>
        <?php endif; ?>
    </div>
</section>