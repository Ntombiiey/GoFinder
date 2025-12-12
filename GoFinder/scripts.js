let currentRating = 0;
let currentActivity = "";

function handleReviewClick(activityId) {
  currentActivity = activityId;
  document.getElementById("reviews-popup-overlay").style.display = "block";
  document.getElementById("reviews-popup").style.display = "block";
  loadReviews();
}

function closePopup() {
  document.getElementById("reviews-popup-overlay").style.display = "none";
  document.getElementById("reviews-popup").style.display = "none";
}

function rate(rating) {
  currentRating = rating;
  const stars = document.querySelectorAll("#user-rating .fa-star");
  stars.forEach((star, index) => {
    star.classList.toggle("active", index < rating);
  });
}

function submitReview() {
  const comment = document.getElementById("user-review").value.trim();

  if (!currentRating || !comment) {
    alert("Please provide both a rating and a comment");
    return;
  }

  const formData = new FormData();
  formData.append("action", "submit");
  formData.append("activity_id", currentActivity);
  formData.append("rating", currentRating);
  formData.append("comment", comment);

  fetch("handle_review.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        document.getElementById("user-review").value = "";
        currentRating = 0;
        const stars = document.querySelectorAll("#user-rating .fa-star");
        stars.forEach((star) => star.classList.remove("active"));
        loadReviews();
      } else {
        alert(data.error || "Failed to submit review");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Failed to submit review");
    });
}

function loadReviews() {
  fetch(
    `handle_review.php?action=get&activity_id=${encodeURIComponent(
      currentActivity
    )}`
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        updateReviewList(data.reviews);
        updateAverageRating(data.averageRating);
      } else {
        console.error(data.error);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

function updateReviewList(reviews) {
  const reviewList = document.getElementById("review-list");
  reviewList.innerHTML = reviews
    .map(
      (review) => `
        <div class="review-item">
            <div class="review-rating">
                ${generateStars(review.rating)}
            </div>
            <div class="review-comment">${review.comment}</div>
            <div class="review-date">${new Date(
              review.created_at
            ).toLocaleDateString()}</div>
        </div>
    `
    )
    .join("");
}

function updateAverageRating(rating) {
  const avgStars = document.querySelector(`#${currentActivity}-average-rating`);
  if (avgStars) {
    const stars = avgStars.querySelectorAll(".fa-star");
    stars.forEach((star, index) => {
      star.classList.toggle("active", index < Math.round(rating));
    });
  }
}

function generateStars(rating) {
  return Array(5)
    .fill()
    .map(
      (_, i) => `<i class="fa-solid fa-star ${i < rating ? "active" : ""}"></i>`
    )
    .join("");
}

// Close popup when clicking outside
document.addEventListener("click", (e) => {
  if (e.target.classList.contains("overlay")) {
    closePopup();
  }
});
