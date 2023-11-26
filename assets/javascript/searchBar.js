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
    }

    // Function that performs the search
    const performSearch = debounce((searchTerm) => {
        fetch(`/search?term=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(movies => {
                resultsContainer.innerHTML = ''; // Clear previous results

                movies.forEach(movie => {
                    // Create the container div for the search result item
                    const resultItem = document.createElement('div');
                    resultItem.className = 'search-result-item';

                    // Create the image element
                    const image = document.createElement('img');
                    image.src = movie.image;
                    image.alt = movie.title;
                    image.id = "movie-search-results";

                    // Create the anchor element for the title
                    const titleLink = document.createElement('a');
                    titleLink.href = `/home/${movie.id}`; // Assuming you have an ID to create a link to the movie details page
                    titleLink.textContent = `${movie.title} (${movie.releaseYear})`;

                    // Append the image and title link to the result item
                    resultItem.appendChild(image);
                    resultItem.appendChild(titleLink);

                    // Append the result item to the results container
                    resultsContainer.appendChild(resultItem);
                });

// Show the results container
                resultsContainer.style.display = 'block';
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
    // Close popout when clicking outside of it
    window.addEventListener('click', function(event) {
        if (!resultsContainer.contains(event.target) && !searchBox.contains(event.target)) {
            resultsContainer.style.display = 'none';
        }
    });

// Close popout on pressing the escape key
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            resultsContainer.style.display = 'none';
        }
    });
});
