// Function to update the display of stars
function updateStarDisplay(selectedRating) {
    const stars = document.querySelectorAll('.rating .star');

    stars.forEach(star => {
        const starValue = parseInt(star.getAttribute('data-value'), 10);

        // If the star's value is less than or equal to the selected rating, add the 'selected-rating' class
        if (starValue <= selectedRating) {
            star.classList.add('selected-rating');
        } else {
            // Otherwise, remove it
            star.classList.remove('selected-rating');
        }
    });
}

// Function to remove hover effects
function removeAllHoverEffects() {
    document.querySelectorAll('.rating .star').forEach(star => {
        star.classList.remove('hover-effect');
    });
}

// Setting event listeners on each star
document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelectorAll('.rating .star').forEach(star => {
        star.addEventListener('mouseover', function() {
            removeAllHoverEffects();

            let hoverUntil = parseInt(this.getAttribute('data-value'), 10);
            for (let i = 1; i <= hoverUntil; i++) {
                document.querySelector(`.rating .star[data-value="${i}"]`).classList.add('hover-effect');
            }
        });

        star.addEventListener('mouseout', function() {
            removeAllHoverEffects();
        });

        star.addEventListener('click', function() {
            let selectedRating = parseInt(this.getAttribute('data-value'), 10);
            // Update the line below to match the ID of the Symfony-generated form field
            document.getElementById('form_id_for_rating_field').value = selectedRating;
            updateStarDisplay(selectedRating);
        });

    });
});
