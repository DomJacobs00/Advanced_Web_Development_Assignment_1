document.querySelectorAll('.btn-danger[data-review-id]').forEach(item => {
    item.addEventListener('click', event => {
        let reviewId = event.target.getAttribute('data-review-id');
        let formAction = '/home/deletereview/' + reviewId; // Update with your actual route
        document.querySelector('#deleteReviewModal form').action = formAction;
    });
});
