<header id="global-nav" class="global-nav global-alert-offset-top">
    <div class="global-nav_content">
        <!-- Logo -->
        <a href="https://linkedin.com" class="app-logo" aria-label="Navigate to Home Page">
            <div class="global-nav_branding-logo">
                <svg width="40" height="40" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="none">
                    <path fill="#0A66C2" d="M12.225 12.225h-1.778V9.44c0-.664-.012-1.519-.925-1.519-.926 0-1.068.724-1.068 1.47v2.834H6.676V6.498h1.707v.783h.024c.348-.594.996-.95 1.684-.925 1.802 0 2.135 1.185 2.135 2.728l-.001 3.14zM4.67 5.715a1.037 1.037 0 01-1.032-1.031c0-.566.466-1.032 1.032-1.032.566 0 1.031.466 1.032 1.032 0 .566-.466 1.032-1.032 1.032zm.889 6.51h-1.78V6.498h1.78v5.727zM13.11 2H2.885A.88.88 0 002 2.866v10.268a.88.88 0 00.885.866h10.226a.882.882 0 00.889-.866V2.865a.88.88 0 00-.889-.864z"/>
                </svg>
            </div>
        </a>

        <!-- Hamburger Button for Mobile -->
        <button class="nav-toggle" aria-label="Toggle Navigation">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        <!-- Navigation Links -->
        <nav class="global-nav_nav" aria-label="Main Navigation">
            <ul class="nav-buttons">
                <li><a href="/">Home</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="/lowongan">Lowongan</a></li>
            </ul>
        </nav>

        <!-- User Profile Section -->
        <div class="global-nav_user">
            <?php if (isset($_SESSION['user_id'])) { ?>
                <div class="user-menu">
                    <button id="user-button" class="user-button" aria-haspopup="true">
                        <img src="/public/svg/person.svg" alt="User Profile Picture" class="profile-pic">
                        <span>Hello, <?php echo $_SESSION['nama']; ?></span>
                    </button>

                    <div id="dropdown-menu" class="dropdown-menu">
                        <a href="/profile" class="dropdown-item">Profile</a>
                        <a href="/applied-jobs" class="dropdown-item">Applied Jobs</a>
                        <a href="/logout" class="dropdown-item">Logout</a>
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
                // Toggle dropdown visibility on click
                userButton.addEventListener('click', (e) => {
                    e.stopPropagation(); // Prevent bubbling to document
                    dropdownMenu.classList.toggle('active');
                    userButton.setAttribute(
                        'aria-expanded',
                        dropdownMenu.classList.contains('active')
                    );
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', () => {
                    dropdownMenu.classList.remove('active');
                    userButton.setAttribute('aria-expanded', 'false');
                });
            }
        });
    </script>
</header>
