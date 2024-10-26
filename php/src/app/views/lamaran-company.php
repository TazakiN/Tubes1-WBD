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
    status_reason_box.innerHTML = data.status_reason;

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

                <h3> Application Verdict </h3>

                <div class="editor-container" id="status-decision-input-box" style="height: 360px;"></div>

                <input type="hidden" name="statusDecision" id="editorContent">
                
                <div class="decision-buttons">
                    <button class="accept-button" id="accept-button" type="button"> Accept </button>
                    <button class="reject-button" id="reject-button" type="button"> Reject </button>
                </div>
            </div>
        `;

        lamaran_body.insertAdjacentHTML("beforeend", status_decision_div);

        var quill = new Quill("#status-decision-input-box", {
            theme: 'snow'
        });

        accept_button = document.getElementById('accept-button');
        reject_button = document.getElementById('reject-button');

        function AJAXPostReason(new_status){
            var reason = quill.root.innerHTML;
            if (reason === "" || reason === null || reason === "<p><br></p>"){
                reason = "Employer gave no reason for this decision";
            }
            const xhr_data = { reason: reason };
            const lamaran_id = location.search.split('lamaran_id=')[1];
            const xhr = new XMLHttpRequest();

            accept_button.disabled= true;
            reject_button.disabled = true;

            xhr.open('POST', `/lamaran/update?lamaran_id=${lamaran_id}&new_status=${new_status}`, true);
            xhr.setRequestHeader('Content-Type', 'application/json');

            xhr.onload = function() {
                try {
                    console.log(xhr.responseText);
                    const response = JSON.parse(xhr.responseText);
                    const toastData = {};
                    if (response.status === 'success') {
                        toastData.success = response.message;
                        showToast(toastData);
                        setTimeout(() => {
                            window.location.href = `lamaran?lamaran_id=${lamaran_id}`;
                        }, 1000);
                    } else {
                        toastData.error = response.message || 'An error occurred while saving this decision';
                        accept_button.disabled= false;
                        reject_button.disabled = false;
                        showToast(toastData);
                    }
                } catch (error) {
                    showToast({
                        error: 'An error occurred while processing server response'
                    });
                    accept_button.disabled= false;
                    reject_button.disabled = false;
                }
            };
            xhr.onerror = function() {
                console.error('An error occurred during the request');
                accept_button.disabled= false;
                reject_button.disabled = false;
            }
            xhr.send(JSON.stringify(xhr_data));
        }

        accept_button.addEventListener("click", function(){
            AJAXPostReason('accepted');
        });

        reject_button.addEventListener("click", function(){
            AJAXPostReason('rejected');
        });
    }
</script>