document.addEventListener("DOMContentLoaded", () => {
    document.body.addEventListener("click", (event) => {
        const target = event.target;
        if (target.classList.contains("preview-image")) {
            openImagePreview(target.getAttribute("data-preview"));
        }
    });
});

function openImagePreview(imageSrc) {
    const overlay = document.createElement("div");
    overlay.classList.add("image-preview-overlay");

    const previewContainer = document.createElement("div");
    previewContainer.classList.add("image-preview-container");

    const image = document.createElement("img");
    image.src = imageSrc;
    image.classList.add("image-preview");

    const closeButton = document.createElement("button");
    closeButton.innerHTML = "&times;";
    closeButton.classList.add("close-preview");

    previewContainer.appendChild(image);
    previewContainer.appendChild(closeButton);
    overlay.appendChild(previewContainer);
    document.body.appendChild(overlay);

    closeButton.addEventListener("click", () => document.body.removeChild(overlay));
    overlay.addEventListener("click", (e) => {
        if (e.target === overlay) document.body.removeChild(overlay);
    });
}
