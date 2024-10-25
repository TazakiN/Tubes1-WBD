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
            <!-- <div class="status-reason">
                WOI BANGSAT MUKA LO KAYA KULIT KONTOL. LU KIRA BISA KERJA KEK GITU ANJING. KELARIN DULU WBD LU TAI ANJING BARU LAMAR KERJA!
            </div> -->
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

            <!-- <div class="files-header">
                <div class="svg-box">
                    <img src="/public/svg/video.svg" alt="Video" class="files-svg">
                </div>
                <h3> Resume Video </h3>
            </div> 

            <video width="100%" height="720" controls>
                <source src="uploads/Resume KSI.mp4" type="video/mp4">
            </video> -->
            
            <button class="delete-button">
                Delete Application
            </button>

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

    var delete_button = `<button class="delete-button" id="delete-button">
                            Delete Application
                        </button>`;

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

    lamaran_body.insertAdjacentHTML("beforeend", delete_button);

    // document.getElementById('delete-button').addEventListener("click", function() {
    //     const lamaran_id = location.search.split('lamaran_id=')[1];
    //     if (confirm("Are you sure you want to delete this application?")) {
    //         const xhr = new XMLHttpRequest();
    //         xhr.open('DELETE', `/lamaran/delete?lamaran_id=${lamaran_id}`, true);
    //         xhr.setRequestHeader('Content-Type', 'application/json');

    //         xhr.onload = function () {
    //             try {
    //                 const response = JSON.parse(xhr.responseText);
    //                 console.log(response.status);
    //                 const toastData = {};
                    
    //                 if (response.status === "success") {
    //                     window.location.href = '/';
    //                 } else {
    //                     toastData.error = response.message || 'An error occured while deleting application';
    //                 }

    //                 showToast(toastData);

    //             } catch (e) {
    //                 showToast({
    //                     error: 'A server response error occured'
    //                 });
    //             }
    //         };

    //         xhr.onerror = function () {
    //             console.error('An error occurred during request');
    //         };

    //         xhr.send();
    //     }
    // });

    document.getElementById('delete-button').addEventListener("click", function() {
            const lamaran_id = location.search.split('lamaran_id=')[1];
            if (confirm("Are you sure you want to delete this application?")) {
                const xhr = new XMLHttpRequest();
                xhr.open('DELETE', `/lamaran/delete?lamaran_id=${lamaran_id}`, true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.onload = function() {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        const toastData = {};
                        if (response.status === 'success') {
                            window.location.href = '/';
                        } else {
                            toastData.error = response.message || 'An error occurred while deleting the application';
                        }
                        showToast(toastData);
                    } catch (error) {
                        showToast({
                            error: 'An error occurred while processing server response'
                        });
                    }
                }
                xhr.onerror = function() {
                    console.error('An error occurred during the request');
                }
                xhr.send();
            }
    });
</script>