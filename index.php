<!DOCTYPE html>
<html>
<head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2014-11-29/FileSaver.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.5.0/jszip.min.js"></script>
<script>
async function extractCBZ() {
    // Fetch the CBZ file from the src directory
    const response = await fetch('comic.cbz');
    // Read the contents of the CBZ file
    const arrayBuffer = await response.arrayBuffer();
    var zip = new JSZip();
    // Load the contents of the CBZ file
    zip.loadAsync(arrayBuffer).then(function(zip) {
        // Get the list of image files in the CBZ file
        var imagePromises = Object.entries(zip.files)
            .filter(([name]) => /.*\.(jpg|jpeg|png)/i.test(name))
            .map(([name, file]) => file.async("blob").then(blob => ({ name, blob })));
        // Wait for all the promises to resolve
        Promise.all(imagePromises).then(images => {
            // Loop through the images
            for (const { name, blob } of images) {
                // Create a new image element
                var img = document.createElement("img");
                // Set the src of the image to the extracted image
                img.src = URL.createObjectURL(blob);
                img.setAttribute("alt",name);
                img.classList.add("cbz-image");
                // Add the image to the HTML page
                document.getElementById("images").appendChild(img);
            }
        }).catch(() => {
            console.error("Error loading images");
        });
    });
}
</script>
<style>
.cbz-image{
    max-width: 80%;
    display: block;
    margin: 0 auto;
}

</style>
</head>
<body>
  
<button id="load" onclick="extractCBZ()">Load CBZ</button>
  <button id="increase-button">Increase Width</button>
    <button id="decrease-button">Decrease Width</button>
  <div id="images" style='max-width: 100%;'></div>
  <script>
    // Get the increase button
    var increaseButton = document.getElementById("increase-button");
    // Get the decrease button
    var decreaseButton = document.getElementById("decrease-button");
    // Get the current max-width value
    var currentMaxWidth = 80;

    // Add an event listener to the increase button
    increaseButton.addEventListener("click", function() {
        // Increase the current max-width value
        currentMaxWidth += 10;
        // Update the max-width of the images
        updateMaxWidth(currentMaxWidth);
    });

    // Add an event listener to the decrease button
    decreaseButton.addEventListener("click", function() {
        // Decrease the current max-width value
        currentMaxWidth -= 10;
        // Update the max-width of the images
        updateMaxWidth(currentMaxWidth);
    });

    function updateMaxWidth(newMaxWidth) {
        // Get all the image elements
        var images = document.getElementsByClassName("cbz-image");
        // Loop through the images
        for (var i = 0; i < images.length; i++) {
            // Set the max-width of the image
            images[i].style.maxWidth = newMaxWidth + "%";
        }
    }
</script>

</body>
</html>
