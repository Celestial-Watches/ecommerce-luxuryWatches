document.addEventListener("DOMContentLoaded", () => {
    document.body.addEventListener("click", (event) => {
        const target = event.target;
        if (target.classList.contains("preview-image")) {
            openImagePreview(target.getAttribute("data-preview"));
        }
    });
});

function openImagePreview(imageSrc) {
    // Create a full-screen overlay
    const overlay = document.createElement("div");
    overlay.classList.add("image-preview-overlay");

    // Create the image container
    const previewContainer = document.createElement("div");
    previewContainer.classList.add("image-preview-container");

    // Create the image element
    const image = document.createElement("img");
    image.src = imageSrc;
    image.classList.add("image-preview");

    // Create a close button
    const closeButton = document.createElement("button");
    closeButton.innerHTML = "&times;";
    closeButton.classList.add("close-preview");

    // Append elements
    previewContainer.appendChild(image);
    previewContainer.appendChild(closeButton);
    overlay.appendChild(previewContainer);
    document.body.appendChild(overlay);

    // Close the preview when clicking the close button or outside the image
    closeButton.addEventListener("click", () => document.body.removeChild(overlay));
    overlay.addEventListener("click", (e) => {
        if (e.target === overlay) document.body.removeChild(overlay);
    });
}
