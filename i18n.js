// Add event listener for when DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Set default language to English
    let language = 'en';
    loadLanguage(language);

    // Handle language switching
    document.getElementById('languageSwitcher').addEventListener('change', function(event) {
        language = event.target.value;
        loadLanguage(language);
    });
});

// Function to load language based on selected value
function loadLanguage(language) {
    // Fetch corresponding language JSON file
    fetch('locales/' + language + '.json')
        .then(response => {
            if (!response.ok) {
                throw new Error("HTTP error " + response.status);
            }
            return response.json();
        })
        .then(data => {
            // Update the text content of elements based on JSON data
            updateTextContent('tr_home', data.tr_home);
            updateTextContent('tr_about_us', data.tr_about_us);
            updateTextContent('tr_patient-tab', data.tr_patient-tab);
            updateTextContent('tr_doctor-tab', data.tr_doctor-tab);
            updateTextContent('tr_admin-tab', data.tr_admin-tab);
        })
        .catch(error => {
            console.error("Error fetching JSON:", error);
        });
}

// Helper function to update text content of a DOM element
function updateTextContent(elementId, newText) {
    let element = document.getElementById(elementId);
    if (element) {
        element.textContent = newText;
    } else {
        console.error(`Element with ID '${elementId}' not found.`);
    }
}
