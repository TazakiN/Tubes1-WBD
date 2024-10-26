<?php
$__headContent = '<link rel="stylesheet" href="/public/css/riwayat.css">';
?>
<section class="riwayat-page-container">
    <h1>Application History</h1>

    <div class="riwayat-container" id="riwayat-container"> </div>

</section>

<script>
    var datas =  <?php echo json_encode($data); ?>;
    var container = document.getElementById('riwayat-container');
    var id;
    if (datas.length !== 0){
        
        datas.forEach(function(data) {

        var riwayat_div = document.createElement('div');
        riwayat_div.className = 'riwayat';

        var company_pic_div = `
            <div class="company-pic">
            <img src="/public/svg/company.svg" alt="company" class="company-pic">
            </div>
        `;

        var riwayat_text_div = document.createElement('div');
        riwayat_text_div.className = 'riwayat-text';

        var position_text = document.createElement('h2');
        position_text.textContent = data.position;

        var company_name_text = document.createElement('p');
        company_name_text.textContent = data.company_name;

        var status_text = document.createElement('div');
        status_text.className = 'status';
        status_text.textContent = data.status;

        switch(data.status) {
            case "accepted":
                status_text.classList.add("accepted");
                break;
            case "rejected":
                status_text.classList.add("rejected");
                break;
            case "waiting":
                status_text.classList.add("waiting");
                break;
        }

        var date_text = document.createElement('p1');

        const date = new Date(data.created_at);
        const options = {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
        };
        const formattedDate = date.toLocaleString('en-GB', options).replace(',', '');
        formattedDate.replace('/', '-').replace('/', '-');


        date_text.textContent = 'Submission Date : ' + formattedDate;

        riwayat_text_div.appendChild(position_text);
        riwayat_text_div.appendChild(company_name_text);
        riwayat_text_div.appendChild(status_text);
        riwayat_text_div.appendChild(date_text);

        riwayat_div.innerHTML = company_pic_div;
        riwayat_div.appendChild(riwayat_text_div);

        riwayat_div.setAttribute('lamaran_id', data.lamaran_id);

        riwayat_div.addEventListener('click', function() {
            id = riwayat_div.getAttribute('lamaran_id');
            window.location.href = `lamaran?lamaran_id=${id}`;
        });

        container.appendChild(riwayat_div);
        });
    } else {
        empty_text = document.createElement('h3');
        empty_text.innerText = "You haven't made any application yet, apply one in job listing!";
        container.appendChild(empty_text);
    }
</script>