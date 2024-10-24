const searchInput = document.getElementById('searchInput');
searchInput.addEventListener('input', debounce(handleSearchInput, 500));

getSelectedValues();

function getSelectedValues() {
    const selectedLocations = Array.from(document.querySelectorAll('input[name="location"]:checked'))
        .map(checkbox => checkbox.value);
    
    const selectedTypes = Array.from(document.querySelectorAll('input[name="type"]:checked'))
        .map(checkbox => checkbox.value);



    console.log("Selected Locations: ", selectedLocations);
    console.log("Selected Types: ", selectedTypes);

    const queryParams = `locations=${encodeURIComponent(selectedLocations.join(','))}&types=${encodeURIComponent(selectedTypes.join(','))}`;

    // Create XMLHttpRequest object
    const xhr = new XMLHttpRequest();

    // Define the type of request and the URL to send to
    xhr.open('GET', `/home${queryParams}`, true);

    // Set up the callback for when the request completes
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            try {
                // Parse the JSON response from the server
                const data = JSON.parse(xhr.responseText);
                console.log('Response from PHP:', data);
            } catch (error) {
                console.error('Error parsing JSON:', error);
            }
        }
    };

    // Handle any errors during the request
    xhr.onerror = function () {
        console.error('Request failed.');
    };

    // Send the GET request
    xhr.send();
}

document.querySelectorAll('input[name="location"], input[name="type"]').forEach(checkbox => {
    checkbox.addEventListener('change', getSelectedValues);
});

function debounce(cb, delay = 500) {
    let debounceTimer;
    return function(...args) {
        const context = this;
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => cb.apply(context, args), delay);
    };
}

function handleSearchInput(event) {
    const query = event.target.value;
    console.log("Search query:", query);
}