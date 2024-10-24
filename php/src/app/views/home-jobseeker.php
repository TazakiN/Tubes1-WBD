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

        <input type="text" placeholder="Search..." class="search-input" id="searchInput">

        <div class="line"></div>
        <div class="line"></div>

        <div class="button-container">
            <a href="/home">
                <button class="job-listing-button"> <img src="/public/svg/eye.svg"> Job Listing</button>
            </a>
            <button class="purry-search-button">Purry Search</button>
        </div>
    </div>
</section>

<script>

function debounce(cb, delay = 500) {
        let debounceTimer;
        return function(...args) {
            const context = this;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => cb.apply(context, args), delay);
        };
    }

    function handleSearchInput(event) {
        const query = event.target.value;
        console.log("Search query:", query);
    }

    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', debounce(handleSearchInput, 500));

</script>