
document.getElementById('findMovieBtn').addEventListener('click', function() {
    var movieName = document.getElementById('movieName').value;
    fetch(`/api/v1/movie/${movieName}`)
    .then(response => response.json())
    .then(data => {
    if(data.error) {
    // If there's an error, display it in the modal body
    document.querySelector('#movieFindModal .modal-body').innerHTML = `<p>${data.error}</p>`;
} else {
    // Set the movie title as the modal title
    document.querySelector('#movieFindModalLabel').innerText = data.Title;

    // Fill the modal body with movie information, including the poster image
    document.querySelector('#movieFindModal .modal-body').innerHTML = `
                    <div class="text-center mb-3">
                        <img src="${data.Poster}" alt="Poster" class="img-fluid">
                    </div>
                    <p><strong>Release Date:</strong> ${data.Released}</p>
                    <p><strong>Director:</strong> ${data.Director}</p>
                    <p><strong>Plot:</strong> ${data.Plot}</p>
                `;

    // Trigger the modal to show
    var modal = new bootstrap.Modal(document.getElementById('movieFindModal'));
    modal.show();
}
})
    .catch(error => console.error('Error:', error));
});

