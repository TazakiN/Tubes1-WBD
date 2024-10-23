<?php
    $__headContent = '<link rel="stylesheet" href="/public/css/lowongan-detail.css">';
?>

<section class="lowongan-detail-section">
    <div class="container">
        <?php
            include "lowongan-detail.php";
        ?>

        <label class="switch">
            <input 
                type="checkbox" 
                id="isOpenSwitch" 
                <?php echo $data['is_open'] ? 'checked' : ''; ?> 
            >
            <span class="slider"></span>
        </label>

        <a href="/lowongan/edit?lowongan_id=<?php echo $data['lowongan_id']?>">
            <button class="action-button">Edit</button>
        </a>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const switchElement = document.getElementById('isOpenSwitch');
    const lowonganId = new URLSearchParams(window.location.search).get('lowongan_id');
    const statusElement = document.getElementById('status');

    switchElement.addEventListener('change', function () {
        const isOpen = switchElement.checked;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', `/lowongan/edit-status?lowongan_id=${lowonganId}`, true);
        xhr.setRequestHeader('Content-Type', 'application/json');

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                console.log('Berhasil memperbarui status:', response.message);
                statusElement.textContent = isOpen ? 'Open' : 'Closed';
                statusElement.className = isOpen ? 'status open' : 'status closed';
            } else {
                console.error('Gagal memperbarui status:', xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error('Terjadi kesalahan dalam request');
        };

        // Kirim data sebagai JSON payload
        const payload = JSON.stringify({ is_open: isOpen });
        xhr.send(payload);
    });
});

</script>
    