window.addEventListener("load", () => {
    const loader = document.querySelector(".loader");
    if (!loader) return; // Exit if loader doesn't exist

    loader.classList.add("loader--hidden");

    loader.addEventListener("transitionend", () => {
        if (loader.parentNode) { // Check if loader still has a parent
            loader.parentNode.removeChild(loader);
        }
    });
});