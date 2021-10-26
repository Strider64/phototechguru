const lightbox = document.createElement('div')
lightbox.id = 'lightbox';

document.body.appendChild(lightbox);

const images = document.querySelectorAll('img')
images.forEach(image => {
    /* Add Event Listener to Images and setting css class to active */
    image.addEventListener('click', () => {
        lightbox.classList.add('active')
        const divBox = document.createElement('div');

        divBox.classList.add('boxStyle')

        /*
         * Set the Image Element, Class and Attributes
         */
        const img = document.createElement('img');
        img.classList.add('imageStyle')
        img.width = 800;
        img.height = 534;

        /*
         * Set the EXIF info for the particular image
         */
        const exif = document.createElement('p');
        exif.textContent = image.getAttribute('data-exif');
        exif.classList.add('displayInfo');
        console.log(exif);
        img.src = image.src // image path

        /* Remove Image For Screen (cleanup) */
        while (lightbox.firstChild) {
            lightbox.removeChild(lightbox.firstChild)
        }

        /* Add Image to Screen */
        lightbox.appendChild(divBox);
        divBox.appendChild(img);
        divBox.appendChild(exif);
    })
})

lightbox.addEventListener('click', () => {
    if (lightbox.hasChildNodes()) {
        lightbox.classList.remove('active'); // Exit Lightbox by removing active css class
    }
})