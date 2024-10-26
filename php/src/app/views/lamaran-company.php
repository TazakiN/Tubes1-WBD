<?php
 $__headContent = '<link rel="stylesheet" href="/public/css/lamaran.css">';
?>

<section>
    <div class="lamaran-container">
        <div class="lamaran-header" id="lamaran-header">
            <h2 class="subheader-title"> <?php echo $data['position'] . " at " . $data['company_name']; ?> </h2>
            <div class="status" id="status"> <?php echo $data['status'] ?> </div>
            <div class="lamaran-date">
                <img src="/public/svg/date.svg" alt="date" class="calendar-pic">
                <h3> 
                    <?php
                        $date = DateTime::createFromFormat('Y-m-d H:i:s.uP', $data['date']);
                        echo $date->format('d-m-Y');
                    ?> 
                </h3>
            </div>
        </div>
        <div class="lamaran-body" id="lamaran-body">

            <!-- <div class="lamaran-notes">
                <?php echo $data['note'] ?>
            </div> -->

            <div class="files-header">
                <div class="svg-box">
                    <img src="/public/svg/cv.svg" alt="CV" class="files-svg">
                </div>
                <h3> Curriculum Vitae </h3>
            </div>

            <embed src= "<?php echo $data['cv'] ?>" type="application/pdf" width="100%" height="1080px">

        </div>
    </div>
</section>

<script>
    var data =  <?php echo json_encode($data); ?>;
    var lamaran_body = document.getElementById('lamaran-body');

    if (data.note !== "" && data.note !== null && data.note !== "<p><br></p>"){
        var note_box = document.createElement('div');
        note_box.classList.add('lamaran-notes');
        note_box.innerHTML=data.note;
        lamaran_body.prepend(note_box);
    }

    if (data.video !== "" && data.video !== null){
        var video_div = 
            `<div class="files-header">
                <div class="svg-box">
                    <img src="/public/svg/video.svg" alt="Video" class="files-svg">
                </div>
                <h3> Resume Video </h3>
            </div> 

            <video width="100%" height="720" controls>
                <source id=video src="" type="video/mp4">
            </video>`;
        var video_area = document.createElement('div');
        video_area.innerHTML = video_div;
        lamaran_body.appendChild(video_area);
        document.getElementById('video').src = data.video;
    }

    var lamaran_header = document.getElementById('lamaran-header');

    if (data.status_reason === "" || data.status_reason === null){
        data.status_reason = "Employer gave no reason for this decision";
    }

    var status_sign = document.getElementById('status');
    var status_reason_box = document.createElement('div');
    status_reason_box.classList.add('status-reason');
    status_reason_box.innerText = data.status_reason;

    switch (data.status) {
        case "accepted":
            status_sign.classList.add("accepted");
            status_reason_box.classList.add("accepted-reason");
            lamaran_header.appendChild(status_reason_box);
            break;
        case "rejected":
            status_sign.classList.add("rejected");
            status_reason_box.classList.add("rejected-reason");
            lamaran_header.appendChild(status_reason_box);
            break;
        case "waiting":
            status_sign.classList.add("waiting");
            break;
    }

    if (data.status === "waiting"){
        var status_decision_div = 
        `
            <div class="status-decision-container" id="status-decision-container">

                <h3> Decision Reasoning </h3>

                <div class="editor-container" id="status-decision-input-box" style="height: 360px;"></div>

                <input type="hidden" name="statusDecision" id="editorContent">
                
                <div class="decision-buttons">
                    <button class="accept-button" id="accept-button" type="submit"> Accept </button>
                    <button class="reject-button" id="reject-button" type="submit"> Reject </button>
                </div>
            </div>
        `;

        lamaran_body.insertAdjacentHTML("beforeend", status_decision_div);

        var quill = new Quill("#status-decision-input-box", {
            theme: 'snow'
        });
    }

</script>