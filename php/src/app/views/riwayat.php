<?php
$__headContent = '<link rel="stylesheet" href="/public/css/riwayat.css">';
?>
<section class="riwayat-page-container">
    <h1>Application History</h1>
    <button class="sort-button">sort</button>

    <div class="riwayat-container" id="riwayat-container"> </div>

</section>

<script>
    var datas =  <?php echo json_encode($data); ?>;
    var container = document.getElementById('riwayat-container');
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

        var date_text = document.createElement('p1');
        date_text.textContent = 'Submission Date : ' + Date(data.created_at);

        riwayat_text_div.appendChild(position_text);
        riwayat_text_div.appendChild(company_name_text);
        riwayat_text_div.appendChild(status_text);
        riwayat_text_div.appendChild(date_text);

        riwayat_div.innerHTML = company_pic_div;
        riwayat_div.appendChild(riwayat_text_div);

        container.appendChild(riwayat_div);
    });
</script>