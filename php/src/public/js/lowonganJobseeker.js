const url = new URL(window.location.href);
const params = new URLSearchParams(url.search);

const selectedLocations = params.get('jenis_lokasi') ? params.get('jenis_lokasi').split(',') : [];
const selectedTypes = params.get('jenis_pekerjaan') ? params.get('jenis_pekerjaan').split(',') : [];

document.querySelectorAll('input[name="location"]').forEach(checkbox => {
    if (selectedLocations.includes(checkbox.value)) {
        checkbox.checked = true;
    } else {
        checkbox.checked = false;
    }
});

document.querySelectorAll('input[name="type"]').forEach(checkbox => {
    if (selectedTypes.includes(checkbox.value)) {
        checkbox.checked = true;
    } else {
        checkbox.checked = false;
    }
});

const searchInput = document.getElementById('searchInput');
searchInput.addEventListener('input', debounce(handleSearchInput, 500));


document.getElementById('reverseOrderBtn').addEventListener('click', function () {  
    const button = this;
    const url = new URL(window.location.href);
    const params = url.searchParams;
    
    const currentSort = params.get('sort');
    let newSort;

    if (currentSort == 'desc') {
        newSort = 'asc';
        button.textContent = 'Sort Descending';
    } else {
        newSort = 'desc';
        button.textContent = 'Sort Ascending'; 
    }

    params.set('sort', newSort);
    url.search = params.toString();

    window.location.href = url.toString();
});

document.querySelectorAll('input[name="location"], input[name="type"]').forEach(checkbox => {
    checkbox.addEventListener('change', getSelectedValues);
});

function getSelectedValues() {
    const selectedLocations = Array.from(document.querySelectorAll('input[name="location"]:checked'))
        .map(checkbox => checkbox.value);
    
    const selectedTypes = Array.from(document.querySelectorAll('input[name="type"]:checked'))
        .map(checkbox => checkbox.value);

    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);
    
    params.set('jenis_lokasi', selectedLocations.join(','));
    params.set('jenis_pekerjaan', selectedTypes.join(','));

    url.search = params.toString();

    console.log("Selected Locations: ", selectedLocations);
    console.log("Selected Types: ", selectedTypes);

    window.location.href = url;
}

function debounce(cb, delay = 1000) {
    let debounceTimer;
    return function(...args) {
        const context = this;
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => cb.apply(context, args), delay);
    };
}

function handleSearchInput(event) {
    const query = event.target.value;
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);

    params.set('searchParams', query);

    url.search = params.toString();
    window.location.href = url;
}

function getUrlParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

document.addEventListener("DOMContentLoaded", function() {
    const searchParamsValue = getUrlParam('searchParams');

    const url = new URL(window.location.href);
    const currentSort = url.searchParams.get('sort');
    const button = document.getElementById('reverseOrderBtn');

    // Set initial button text
    if (currentSort === 'desc') {
        button.textContent = 'Sort Ascending';
    } else {
        button.textContent = 'Sort Descending';
    }
    

    if (searchParamsValue) {
        document.getElementById('searchInput').value = searchParamsValue;
    }
    document.getElementById('searchInput').focus();
});