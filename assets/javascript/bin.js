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