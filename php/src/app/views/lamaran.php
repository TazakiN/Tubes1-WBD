<?php
 $__headContent = '<link rel="stylesheet" href="/public/css/lamaran.css">';
?>

<section class="form-section">
    <div class="form-container">
        <h1 class="header-title">Applying for</h1>
        <h2 class="subheader-title"> Senior HR </h2>
        <p class="error-msg">
            <?php if (isset($errorMsg)) {
                echo "$errorMsg";
            } ?>
        </p>

        <form class="form" method="post" id="lamarform">

            <div class="upload-container"> 
                <div class="upload-group">
                    <button class="upload-button"> CV Upload </button>
                    <label class="file-name"> NAMA FILE </label>
                </div>
                <div class="upload-group">
                    <button class="upload-button"> Video Upload </button>
                    <label class="file-name"> NAMA FILE </label>
                </div>
            </div>

            <h3> Personal Note </h3>

            <div class="editor-container" id="notes-container" style="height: 360px;"></div>

            <input type="hidden" name="editorContent" id="editorContent">

            <div class="submit-button-container">
                <button class="submit-button" type="submit"> Submit </button>
            </div>
        </form>
    </div>
</section>

<script>
    var quill = new Quill('#notes-container', {
        theme: 'snow'
    });

    function submitForm() {
        var editorContent = quill.root.innerHTML;
        document.getElementById('editorContent').value = editorContent;
    }

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