<section class="form-section">
    <div class="form-container">
        <h2 class="header-title">LinkInPurry</h2>

        <p class="error-msg">
            <?php if (isset($errorMsg)) {
                echo "$errorMsg";
            } ?>
        </p>

        <div class="role-selection">
            <button type="button" id="btnJobSeeker" class="role-button active">JobSeeker</button>
            <button type="button" id="btnCompany" class="role-button">Company</button>
        </div>

        <form class="form" method="post" id="registerForm">
            <div class="form-group">
                <label for="nama" class="input-label">Nama</label><br>
                <input class="input" type="text" id="nama" name="nama" placeholder="Nama" required>
            </div>

            <div class="form-group">
                <label for="email" class="input-label">Email</label><br>
                <input class="input" type="email" id="email" name="email" placeholder="Email" required>
            </div>

            <input type="hidden" id="role" name="role" value="jobseeker">

            <div id="companyFields" class="company-fields hidden">
                <div class="form-group">
                    <label for="lokasi" class="input-label">Lokasi</label><br>
                    <input class="input" type="text" id="lokasi" name="lokasi" placeholder="Lokasi Perusahaan">
                </div>

                <div class="form-group">
                    <label for="about" class="input-label">Tentang Perusahaan</label><br>
                    <textarea class="input" id="about" name="about" placeholder="Deskripsi singkat tentang perusahaan"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="input-label">Password</label><br>
                <input class="input" type="password" id="password" name="password" placeholder="Password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password" class="input-label">Confirm Password</label><br>
                <input class="input" type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
            </div>

            <div class="form-group">
                <button class="button" type="submit">Register</button>
            </div>
        </form>

        <div class="note">
            <p>Already have an account?</p>
            <a class="text-link" href="/login">Login here</a>
        </div>
    </div>
</section>

<script>
    const btnJobSeeker = document.getElementById('btnJobSeeker');
    const btnCompany = document.getElementById('btnCompany');
    const roleInput = document.getElementById('role');
    const companyFields = document.getElementById('companyFields');

    const switchRole = (role) => {
        roleInput.value = role;
        if (role === 'jobseeker') {
            companyFields.classList.add('hidden');
            btnJobSeeker.classList.add('active');
            btnCompany.classList.remove('active');
            document.getElementById('lokasi').required = false;
            document.getElementById('about').required = false;
        } else {
            companyFields.classList.remove('hidden');
            btnCompany.classList.add('active');
            btnJobSeeker.classList.remove('active');
            document.getElementById('lokasi').required = true;
            document.getElementById('about').required = true;
        }
    };

    btnJobSeeker.addEventListener('click', () => switchRole('jobseeker'));
    btnCompany.addEventListener('click', () => switchRole('company'));
</script>