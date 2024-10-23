<?php
    $__headContent = '<link rel="stylesheet" href="/public/css/lowongan-detail.css">';
?>

<section class="lowongan-detail-section">
    <div class="container">
        <?php
            include "lowongan-detail.php";
        ?>

        <div class="edit-section">
            <a href="/lowongan/edit?lowongan_id=<?php echo $data['lowongan_id']?>">
                <button class="action-button">Edit</button>
            </a>

            <div class="status-container">
                <label for="isOpenSwitch" class="status-label">Status:</label>
                <div class="switch-container">
                    <label class="switch">
                        <input 
                            type="checkbox" 
                            id="isOpenSwitch" 
                            <?php echo $data['is_open'] ? 'checked' : ''; ?> 
                        >
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>

            <button class="delete-button" id="deleteButton">Delete</button>
        </div>


        <!-- data lamarans -->
        <div class="applicants">
            <h2>Applicants</h2>
            <?php if (!empty($data['lamarans'])): ?>
                <?php foreach ($data['lamarans'] as $lamaran): ?>
                    <div class="applicant">
                        <span class="applicant-name"><?php echo $lamaran->nama; ?></span>
                        <div class="applicant-status">
                            <span class="status <?php echo $lamaran->status; ?>"><?php echo ucfirst($lamaran->status); ?></span>
                            <a href="/lamaran?lamaran_id=<?php echo $lamaran->lamaran_id; ?>">
                                <button class="details-button">Details</button>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No applicants available.</p>
            <?php endif; ?>
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

        const payload = JSON.stringify({ is_open: isOpen });
        xhr.send(payload);
    });

    deleteButton.addEventListener('click', function () {
            if (confirm('Are you sure you want to delete this lowongan?')) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', `/lowongan/delete?lowongan_id=<?= $data['lowongan_id'] ?>`, true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onload = function () {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        console.log('Lowongan berhasil dihapus:', response.message);
                        alert('Lowongan berhasil dihapus');
                        window.location.href = '/'; 
                    } else {
                        console.error('Gagal menghapus lowongan:', xhr.statusText);
                        alert('Gagal menghapus lowongan');
                    }
                };

                xhr.onerror = function () {
                    console.error('Terjadi kesalahan dalam request');
                };

                const payload = JSON.stringify({ lowongan_id: lowonganId });
                xhr.send(payload);
            }
        });
});

</script>
    