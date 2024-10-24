const searchInput = document.getElementById('searchInput');
searchInput.addEventListener('input', debounce(handleSearchInput, 500));

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
    if (searchParamsValue) {
        document.getElementById('searchInput').value = searchParamsValue;
    }
    document.getElementById('searchInput').focus();
});