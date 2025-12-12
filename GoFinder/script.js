function nav(){
    window.location.href="http://localhost/gofinder/GoFinder/sign_up.php";
}

function toggleFilterOptions() {
    var filterOptions = document.getElementById("filterOptions");
    if (filterOptions.style.display === "block") {
        filterOptions.style.display = "none";
    } else {
        filterOptions.style.display = "block";
    }
}


// Function to apply the filter
function applyFilter(filterValue) {
    toggleFilterOptions(); // Hide filter options after selection

    // Display the selected filter or redirect to search results based on filter
    // Replace this section with actual search logic if needed
    alert("Filter applied: " + filterValue); // Replace with code to show filtered results
}

// Optional: Close the filter options when clicking outside
window.onclick = function(event) {
    if (!event.target.matches('.filter-btn')) {
        var filterOptions = document.getElementById("filterOptions");
        if (filterOptions.style.display === "block") {
            filterOptions.style.display = "none";
        }
    }
}

// Slideshow code
let slideIndex = 0;
showSlides();

function plusSlides(n) {
    slideIndex += n;
    showSlides();
}

function showSlides() {
    let i;
    let slides = document.getElementsByClassName("mySlides");
    
    if (slideIndex >= slides.length) { 
        slideIndex = 0; 
    }
    
    if (slideIndex < 0) { 
        slideIndex = slides.length - 1; 
    }
    
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    slides[slideIndex].style.display = "block";
}


setInterval(function() {
    plusSlides(1);
}, 3000);
