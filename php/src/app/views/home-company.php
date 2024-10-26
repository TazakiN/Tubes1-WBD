<?php
    $__headContent = '<link rel="stylesheet" href="/public/css/home-company.css">
    <script src="/public/js/lowonganJobseeker.js" defer></script>';
?>
<section class="home-section">
    <div class="header">
        <div class="search-bar">
        <input type="text" class="search-input" placeholder="Search..." id="searchInput">
        <button id="reverseOrderBtn" class="filter-button">Reverse Order</button>
            <div class="check-container">
                <label class="check-item"><input type="checkbox" name="location" value="On-site" checked>On-site</label>
                <label class="check-item"><input type="checkbox" name="location" value="Hybrid" checked>Hybrid</label>
                <label class="check-item"><input type="checkbox" name="location" value="Remote" checked>Remote</label>
            </div>

            <div class="check-container">
                <label class="check-item"><input type="checkbox" name="type" value="Internship" checked>Internship</label>
                <label class="check-item"><input type="checkbox" name="type" value="Part-time" checked>Part-time</label>
                <label class="check-item"><input type="checkbox" name="type" value="Full-time" checked>Full-time</label>
            </div>
        </div>
        <h1>Position Listing at, <?php echo $_SESSION["nama"] ?></h1>
    </div>

    <a href="/lowongan/add" class="add-position">
        <span class="add-icon">+</span>
        Add new hiring position
    </a>

    <hr>

    <?php if($data['lowongans'] == null) { ?>
            <div class="empty-state">
                <img src="/public/svg/empty.svg" alt="Empty State">
                <h2>No positions found</h2>
                <p>There are no positions available according to your search criteria. <br> Add a new position to get started.</p>
            </div>
        <?php } else { ?>
    <div class="job-grid">
        <?php foreach ($data["lowongans"] as $v): ?>
            <div class="job-card-container">
                <a class="job-card" href="/lowongan?lowongan_id=<?= $v->lowongan_id ?>">
                    <div class="profile-icon">
                        <svg class="svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                </a>
                <button class="delete-btn" data-id="<?= $v->lowongan_id ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 6h18"></path>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </button>
            </div>
        <?php endforeach; ?>
        <?php } ?>  
    </div>

    <div class="pagination">
    <?php 
        $currentPage = $data['page'];
        $totalPages = $data['totalPage'];

        $queryParams = [];
        $_SERVER['QUERY_STRING'] ? parse_str($_SERVER['QUERY_STRING'], $queryParams) : parse_str("", $queryParams);

        if ($currentPage > 1): 
            $queryParams['page'] = $currentPage - 1;
            ?>
            <a href="/?<?= http_build_query($queryParams) ?>" class="pagination-btn">&larr;</a>
        <?php else: ?>
            <a href="#" class="pagination-btn disabled">&larr;</a>
        <?php endif;

        if ($currentPage > 3): 
            $queryParams['page'] = 1;
            ?>
            <a href="/?<?= http_build_query($queryParams) ?>" class="pagination-btn">1</a>
            <?php if ($currentPage > 4): ?>
                <span class="pagination-dots">...</span>
            <?php endif;
        endif;

        for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): 
            $queryParams['page'] = $i;
            ?>
            <a href="/?<?= http_build_query($queryParams) ?>" 
               class="pagination-btn <?= $currentPage === $i ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor;

        if ($currentPage < $totalPages - 2): 
            if ($currentPage < $totalPages - 3): ?>
                <span class="pagination-dots">...</span>
            <?php endif; 
            $queryParams['page'] = $totalPages; ?>
            <a href="/?<?= http_build_query($queryParams) ?>" class="pagination-btn">
                <?= $totalPages ?>
            </a>
        <?php endif;

        if ($currentPage < $totalPages): 
            $queryParams['page'] = $currentPage + 1;
            ?>
            <a href="/?<?= http_build_query($queryParams) ?>" class="pagination-btn">&rarr;</a>
        <?php else: ?>
            <a href="#" class="pagination-btn disabled">&rarr;</a>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const lowonganId = this.getAttribute('data-id');
            const row = this.closest('tr') || this.closest('.position-card'); 

            if (confirm('Are you sure you want to delete this position?')) {
                const xhr = new XMLHttpRequest();
                xhr.open('DELETE', '/lowongan/delete?lowongan_id=' + lowonganId, true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onload = function () {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === "success") {
                            window.location.reload();
                        } else {
                            showToast({
                                error: response.message || 'Terjadi kesalahan saat menghapus lowongan'
                            });
                        }
                    } catch (e) {
                        showToast({
                            error: 'Terjadi kesalahan saat memproses respons server'
                        });
                    }
                };

                xhr.onerror = function () {
                    showToast({
                        error: 'Terjadi kesalahan koneksi. Silakan coba lagi.'
                    });
                };

                const payload = JSON.stringify({ lowongan_id: lowonganId });
                xhr.send(payload);
            }
        });
    });
});
</script>