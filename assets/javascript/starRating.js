// Function to update the display of stars
function updateStarDisplay(selectedRating) {
    const stars = document.querySelectorAll('.rating .star');

    stars.forEach(star => {
        const starValue = parseInt(star.getAttribute('data-value'), 10);

        if (starValue <= selectedRating) {
            star.classList.add('selected-rating');
        } else {
            star.classList.remove('selected-rating');
        }
    });
}

function removeAllHoverEffects() {
    document.querySelectorAll('.rating .star').forEach(star => {
        star.classList.remove('hover-effect');
    });
}

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
            // Update the field with the selected rating
            document.getElementById('review_form_rating').value = selectedRating;
            updateStarDisplay(selectedRating);

        });
    });
});