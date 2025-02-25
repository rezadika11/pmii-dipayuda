const scrollToTopButton = document.getElementById("scrollToTop");

window.addEventListener("scroll", () => {
    if (window.pageYOffset > 300) {
        scrollToTopButton.classList.remove("hidden");
    } else {
        scrollToTopButton.classList.add("hidden");
    }
});

scrollToTopButton.addEventListener("click", () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
});
