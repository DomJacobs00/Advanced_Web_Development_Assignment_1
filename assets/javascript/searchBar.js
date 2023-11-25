document.addEventListener('DOMContentLoaded', () => {
    const searchBox = document.querySelector('#search-box');
    const resultsContainer = document.querySelector('#search-results');

    // Debounce function to limit the rate at which a function can fire
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    };

    // Function that performs the search
    const performSearch = debounce((searchTerm) => {
        fetch(`/search?term=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(movies => {
                resultsContainer.innerHTML = ''; // Clear previous results

                movies.forEach(movie => {
                    const resultItem = document.createElement('div');
                    resultItem.className = 'search-result-item';
                    resultItem.innerHTML = `
                        <div class="movie-title">${movie.title} (${movie.year})</div>
                        <div class="movie-cast">${movie.cast.join(', ')}</div>
                    `;
                    resultsContainer.appendChild(resultItem);
                });
            }).catch(error => {
            console.error('Error fetching search results:', error);
        });
    }, 300); // Wait for 300 ms after the user has stopped typing

    // Event listener for the input event
    searchBox.addEventListener('input', (event) => {
        const searchTerm = event.target.value;

        if (searchTerm.length > 2) { // Adjust this threshold as needed
            performSearch(searchTerm);
        } else {
            resultsContainer.innerHTML = ''; // Clear results if the searchTerm is too short
        }
    });
});
