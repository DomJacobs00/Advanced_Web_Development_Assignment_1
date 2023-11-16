/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)

import './styles/app.css';
import  './bootstrap';


// movie (movies/movie.html.twig)
document.querySelectorAll('.rating .star').forEach(star => {
    star.addEventListener('mouseover', function() {
        // Remove hover-effect class from all stars
        removeAllHoverEffects();

        // Add hover-effect class to this star and all previous stars
        let hoverUntil = parseInt(this.getAttribute('data-value'));
        for (let i = 1; i <= hoverUntil; i++) {
            document.querySelector(`.rating .star[data-value="${i}"]`).classList.add('hover-effect');
        }
    });

    star.addEventListener('mouseout', function() {
        // Remove hover-effect class from all stars
        removeAllHoverEffects();
    });

});

function removeAllHoverEffects() {
    document.querySelectorAll('.rating .star').forEach(star => {
        star.classList.remove('hover-effect');
    });
}

// for movie searching in the home screen

document.getElementById('movieSearch').addEventListener('input', function(event) {
    const searchTerm = event.target.value;

    fetch('?search=' + encodeURIComponent(searchTerm))
        .then(response => response.json())
        .then(data => {
            const movieListContainer = document.getElementById('movieList');
            movieListContainer.innerHTML = ''; // Clear current movies

            data.movies.forEach(movie => {
                // Construct HTML for each movie
                const movieHtml = `
                    <div class="col">
                        <div class="card h-100" style="width: 18rem;">
                            <img src="${movie.image}" class="card-img-top" alt="${movie.title}">
                            <div class="card-body">
                                <h5 class="card-title">${movie.title}</h5>
                                <p class="card-text">${movie.shortDescription}</p>
                                <a href="/movie/${movie.id}" class="btn btn-primary">Expand</a>
                            </div>
                        </div>
                    </div>
                `;
                movieListContainer.innerHTML += movieHtml;
            });
        })
        .catch(error => {
            console.error('Error:', error);
            // Handle or display the error
        });
});


