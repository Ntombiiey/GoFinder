let loggedIn = true; // This will be dynamically managed based on actual login state
let userPreviousRating = null;
let currentActivity = "";

// Open the popup and load data
function openPopup(activityId) {
  document.getElementById("reviews-popup-overlay").style.display = "block";
  document.getElementById("reviews-popup").style.display = "block";
  loadReviews(activityId);
  loadUserRating(activityId);
}

// Close the popup
function closePopup() {
  document.getElementById("reviews-popup-overlay").style.display = "none";
  document.getElementById("reviews-popup").style.display = "none";
}

// Redirect to login if not logged in
function handleReviewClick(activityId) {
  if (!loggedIn) {
    localStorage.setItem("redirectAfterLogin", activityId);
    window.location.href = "/login.php";
  } else {
    openPopup(activityId);
  }
}

// Fetch existing reviews and ratings from the database (MySQL)
async function loadReviews(activityId) {
  currentActivity = activityId;
  const reviewList = document.getElementById("review-list");
  reviewList.innerHTML = "Loading reviews...";

  const formData = new FormData();
  formData.append("action", "load");
  formData.append("activity_id", activityId);

  try {
    const response = await fetch("handle_reviews.php", {
      method: "POST",
      body: formData,
    });
    const reviews = await response.json();

    reviewList.innerHTML = "";

    // Display reviews
    if (reviews.length > 0) {
      reviews.forEach((review) => {
        const reviewDiv = document.createElement("div");
        reviewDiv.className = "review-item";
        reviewDiv.innerHTML = `
                    <div class="review-rating">${"★".repeat(
                      review.rating
                    )}</div>
                    <div class="review-text">${review.review_text}</div>
                    <div class="review-date">${review.created_at}</div>
                `;
        reviewList.appendChild(reviewDiv);
      });
    } else {
      reviewList.innerHTML = "No reviews yet. Be the first to review!";
    }

    // Highlight average stars
    highlightStars(averageStars, data.averageRating);
  } catch (error) {
    reviewList.innerHTML = "Error fetching reviews.";
    console.error("Error fetching reviews:", error);
  }
}

// Fetch and highlight the user's previous rating (MySQL)
async function loadUserRating(activityId) {
  try {
    const response = await fetch(`/api/ratings/${activityId}/user`);
    const data = await response.json();
    if (data.userRating) {
      userPreviousRating = data.userRating;
      highlightStars(document.getElementById("user-rating"), data.userRating);
    }
  } catch (error) {
    console.error("Error fetching user rating:", error);
  }
}

// Submit a new review and rating
async function submitReview() {
  const activityId = "pretoria-zoo"; // Replace with dynamic activity ID
  const reviewText = document.getElementById("user-review").value;
  const rating = userPreviousRating || 5; // Default to 5 stars if no rating selected

  /*const reviewPayload = {
    rating: rating,
    review: reviewText,
  };*/
  const formData = new FormData();
  formData.append("action", "submit");
  formData.append("activity_id", currentActivity);
  formData.append("rating", rating);
  formData.append("review", reviewText);

  try {
    const response = await fetch("handle_reviews.php", {
      method: "POST",
      body: formData,
    });
    const data = await response.text();

    if (data === "success") {
      alert("Review submitted successfully!");
      loadReviews(currentActivity);
      closePopup();
    } else {
      alert("Failed to submit review.");
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Error submitting review.");
  }
}

// Helper function to highlight stars based on rating
function highlightStars(starContainer, rating) {
  const stars = starContainer.querySelectorAll("i");
  stars.forEach((star, index) => {
    star.classList.remove("active");
    if (index < rating) {
      star.classList.add("active");
    }
  });
}

// Set rating by clicking stars
function rate(starRating) {
  userPreviousRating = starRating;
  highlightStars(document.getElementById("user-rating"), starRating);
}
