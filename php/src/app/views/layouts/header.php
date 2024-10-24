<header id="global-nav" class="global-nav global-alert-offset-top">
    <div class="global-nav_content">
        <!-- Logo -->
        <a href="/" class="app-logo" aria-label="Navigate to Home Page">
            <div class="global-nav_branding-logo">
                <img src="/public/assets/Linkin.png" alt="LinkinPurry Logo">
            </div>
        </a>

        <!-- User Profile Section -->
        <div class="global-nav_user">
            <?php if (isset($_SESSION['user_id'])) { ?>
                <div class="user-menu">
                    <button id="user-button" class="user-button" aria-haspopup="true">
                        <img src="/public/svg/person.svg" alt="User Profile Picture" class="profile-pic">
                        <span>Hello, <span id="displayNama"><?php echo $_SESSION['nama']; ?></span></span>
                    </button>

                    <div id="dropdown-menu" class="dropdown-menu">
                        <a href="/profile" class="dropdown-item">Profile</a>
                        <?php if ($_SESSION['role'] === 'jobseeker') { ?>
                            <a href="/riwayat" class="dropdown-item">History</a>
                        <?php } ?>
                        <a href="/logout" class="dropdown-item logout">Logout</a>
                    </div>
                </div>
            <?php } else { ?>
                <a href="/login" class="nav-button">Login</a>
                <a href="/register" class="nav-button">Register</a>
            <?php } ?>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const userButton = document.getElementById('user-button');
            const dropdownMenu = document.getElementById('dropdown-menu');

            if (userButton && dropdownMenu) {
                userButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('active');
                    userButton.setAttribute(
                        'aria-expanded',
                        dropdownMenu.classList.contains('active')
                    );
                });

                document.addEventListener('click', () => {
                    dropdownMenu.classList.remove('active');
                    userButton.setAttribute('aria-expanded', 'false');
                });
            }
        });
    </script>
</header>
