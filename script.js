function nav(){
windows.href.url = "sign_up.html";
}
function toggleFilterOptions() {
    var filterOptions = document.getElementById("filterOptions");
    if (filterOptions.style.display === "block") {
        filterOptions.style.display = "none";
    } else {
        filterOptions.style.display = "block";
    }
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

/*function redirectFunction () {
    windows.href.url = "sign_up.html";
    }*/
