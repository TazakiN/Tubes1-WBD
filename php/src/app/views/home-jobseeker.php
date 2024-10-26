<?php
    $__headContent ='<link rel="stylesheet" href="/public/css/home.css">';
?>
<section class="home-page-container">
    <div class="home-container">
        <h1><span class="highlight">Welcome,</span> <?php echo $data['nama']; ?></h1>
        <p>Ready to find your dream job ?</p>
        <div class="line"></div>
        <div class="profile-card">
            <div class="profile-picture">
                <img src="/public/svg/profile.svg" alt="profile">
            </div>
            
            <div class="card-description">
                <h2> <?php echo $data['nama']; ?> </h2>
                <div class="button-container">
                    <a href='/edit-profile'>
                        <button class="edit-btn">Edit</button>
                    </a>
                    <a href="/riwayat">
                        <button class="history-btn">History</button>
                    </a>
                </div> 
            </div>
        </div>

        <div class="line"></div>
        <div class="line"></div>

        <p1>Discover thousands of jobs</p1>

        <div class="line"></div>
        <div class="line"></div>

        <input type="text" placeholder="Search..." autocomplete="off" class="search-input" id="searchInput">

        <div class="line"></div>
        <div class="line"></div>

        <div class="button-container">
            <a href="/job-listing">
                <button class="job-listing-button"> <img src="/public/svg/eye.svg"> Job Listing</button>
            </a>
            <button class="purry-search-button" id="searchButton">Purry Search</button>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');

    function performSearch() {
        const searchValue = searchInput.value.trim();
        const baseUrl = '/job-listing';

        const url = searchValue ? 
            `${baseUrl}?searchParams=${encodeURIComponent(searchValue)}` : 
            baseUrl;
            
        window.location.href = url;
    }

    searchInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            performSearch();
        }
    });

    searchButton.addEventListener('click', performSearch);
});
</script>