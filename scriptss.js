let loggedIn = false;  // This will be dynamically managed based on actual login state
let userPreviousRating = null;

// Open the popup and load data
function openPopup(activityId) {
    document.getElementById('reviews-popup-overlay').style.display = 'block';
    document.getElementById('reviews-popup').style.display = 'block';
    loadReviews(activityId);
    loadUserRating(activityId);
}

// Close the popup
function closePopup() {
    document.getElementById('reviews-popup-overlay').style.display = 'none';
    document.getElementById('reviews-popup').style.display = 'none';
}

// Redirect to login if not logged in
function handleReviewClick(activityId) {
    if (!loggedIn) {
        localStorage.setItem('redirectAfterLogin', activityId);
        window.location.href = 'file:///C:/xampp/htdocs/Projects/Go%20Finder/Sample%205/login.php';
    } else {
        openPopup(activityId);
    }
}

// Fetch existing reviews and ratings from the database (PHP backend)
async function loadReviews(activityId) {
    const reviewList = document.getElementById('review-list');
    reviewList.innerHTML = 'Loading reviews...'; // Show loading message
    try {
        const response = await fetch(`file:///C:/xampp/htdocs/Projects/Go%20Finder/Sample%205/get_reviews.php?activityId=${activityId}`);
        const data = await response.json();
        reviewList.innerHTML = ''; // Clear loading message

        const averageStars = document.getElementById(`${activityId}-average-rating`);

        // Display reviews
        if (data.reviews && data.reviews.length > 0) {
            data.reviews.forEach(review => {
                const reviewDiv = document.createElement('div');
                reviewDiv.innerHTML = `<strong>${review.name}</strong> - "${review.comment}"`;
                reviewList.appendChild(reviewDiv);
            });
        } else {
            reviewList.innerHTML = 'No reviews yet. Be the first to review!';
        }

        // Highlight average stars
        highlightStars(averageStars, data.averageRating);
    } catch (error) {
        reviewList.innerHTML = 'Error fetching reviews.';
        console.error('Error fetching reviews:', error);
    }
}

// Fetch and highlight the user's previous rating (PHP backend)
async function loadUserRating(activityId) {
    try {
        const response = await fetch(`file:///C:/xampp/htdocs/Projects/Go%20Finder/Sample%205/get_user_rating.php?activityId=${activityId}`);
        const data = await response.json();
        if (data.userRating) {
            userPreviousRating = data.userRating;
            highlightStars(document.getElementById('user-rating'), data.userRating);
        }
    } catch (error) {
        console.error('Error fetching user rating:', error);
    }
}

// Submit a new review and rating
async function submitReview(activityId) {
    const reviewText = document.getElementById('user-review').value;
    const rating = userPreviousRating || 5;  // Default to 5 stars if no rating selected

    const reviewPayload = {
        activityId: activityId,
        rating: rating,
        review: reviewText,
    };

    try {
        const response = await fetch(`file:///C:/xampp/htdocs/Projects/Go%20Finder/Sample%205/submit_review.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(reviewPayload),
        });

        if (response.ok) {
            alert('Review submitted successfully!');
            loadReviews(activityId);  // Reload reviews after submission
            closePopup();  // Close the popup after submission
        } else {
            alert('Failed to submit review.');
        }
    } catch (error) {
        console.error('Error submitting review:', error);
        alert('Error submitting review.');
    }
}

// Helper function to highlight stars based on rating
function highlightStars(starContainer, rating) {
    const stars = starContainer.querySelectorAll('i');
    stars.forEach((star, index) => {
        star.classList.remove('active');
        if (index < rating) {
            star.classList.add('active');
        }
    });
}

// Set rating by clicking stars
function rate(starRating) {
    userPreviousRating = starRating;
    highlightStars(document.getElementById('user-rating'), starRating);
}

