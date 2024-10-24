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
            try {
                const response = JSON.parse(xhr.responseText);
                const toastData = {};
                if (xhr.status === 200) {
                    statusElement.textContent = isOpen ? 'Open' : 'Closed';
                    statusElement.className = isOpen ? 'status open' : 'status closed';
                    toastData.success = response.message;
                } else if (xhr.status === 403) {
                    toastData.error = response.message;
                } else {
                    toastData.error = response.message || 'Terjadi kesalahan saat memperbarui status';
                }

                showToast(toastData);
            } catch (error) {
                showToast({
                    error: 'Terjadi kesalahan saat memproses respons server'
                });   
            }
        };

        xhr.onerror = function () {
            console.error('Terjadi kesalahan dalam request');
        };

        const payload = JSON.stringify({ is_open: isOpen });
        xhr.send(payload);
    });

    deleteButton.addEventListener('click', function () {
        const lowonganId = <?php echo $data['lowongan_id']; ?>;
        if (confirm('Are you sure you want to delete this offer?')) {
            const xhr = new XMLHttpRequest();
            xhr.open('DELETE', `/lowongan/delete`, true);
            xhr.setRequestHeader('Content-Type', 'application/json');

            xhr.onload = function () {
                try {
                    const response = JSON.parse(xhr.responseText);
                    
                    const toastData = {};
                    
                    if (xhr.status === 200) {
                        window.location.href = '/';
                    } else {
                        toastData.error = response.message || 'Terjadi kesalahan saat menghapus lowongan';
                    }

                    showToast(toastData);

                } catch (e) {
                    showToast({
                        error: 'Terjadi kesalahan saat memproses respons server'
                    });
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
    