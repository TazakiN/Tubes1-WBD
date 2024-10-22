<?php
 $__headContent = '<link rel="stylesheet" href="/public/css/lamaran.css">';
?>

<section class="form-section">
    <div class="form-container">
        <h1 class="header-title">Applying for</h1>
        <h2 class="subheader-title"> <?php echo $data['position'] . " at " . $data['company_name']; ?> </h2>
        <p class="error-msg" id="error-msg">
            <?php if (isset($errorMsg)) {
                echo "$errorMsg";
            } ?>
        </p>

        <form class="form" method="post" id="lamarform" enctype="multipart/form-data">
            <div class="upload-container"> 
                <div class="upload-group">
                    <input type="file" name="cvInput" id="cvInput"  style="display: none;" accept="application/pdf"/>
                    <button type="button" class="upload-button" id="cvInputButton"> CV Upload </button>
                    <label class="file-name" id="cvNameLabel"> Upload PDF file </label>
                </div>
                <div class="upload-group">
                    <input type="file" name="videoInput" id="videoInput" style="display: none;" accept="video/mp4"/>
                    <button type="button" class="upload-button" id="videoInputButton"> Video Upload </button>
                    <label class="file-name" id="videoNameLabel"> Upload MP4 Video (Optional) </label>
                </div>
            </div>

            <h3> Personal Note </h3>

            <div class="editor-container" id="notes-container" style="height: 360px;"></div>

            <input type="hidden" name="noteInput" id="editorContent">

            <div class="submit-button-container">
                <button class="submit-button" type="submit" id="submit-button"> Submit </button>
            </div>
        </form>
    </div>
</section>

<script>
    var quill = new Quill('#notes-container', {
        theme: 'snow'
    });

    document.getElementById('lamarform').addEventListener('submit', function(event) {
        var quillContent = quill.root.innerHTML;
        document.getElementById('editorContent').value = quillContent;
    });

    document.getElementById('cvInputButton').addEventListener('click', function() {
        document.getElementById('cvInput').click();
    });

    document.getElementById('videoInputButton').addEventListener('click', function() {
        document.getElementById('videoInput').click();
    });

    document.getElementById('cvInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) {
            document.getElementById('cvNameLabel').textContent = 'No File Selected';
        } else {
            document.getElementById('cvNameLabel').textContent = file.name;
        }
    });

    document.getElementById('videoInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) {
            document.getElementById('videoNameLabel').textContent = 'No File Selected';
        } else {
            document.getElementById('videoNameLabel').textContent = file.name;
        }
    });
</script>