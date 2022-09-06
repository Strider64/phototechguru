const lightbox = document.createElement('div')


lightbox.classList.add('lightbox');

document.body.appendChild(lightbox);


const images = document.querySelectorAll('img')
images.forEach(image => {
    /* Add Event Listener to Images and setting css class to active */
    image.addEventListener('click', () => {
        lightbox.classList.add('active');
        document.querySelector('.content').style.display = 'none';
        //document.querySelector('.pagination').style.display = 'none';

        /*
         * Create Image portion of LightBox
         */
        let galleryImage = document.createElement('img');
        galleryImage.classList.add('galleryImage');
        galleryImage.width = 800;
        galleryImage.height = 534;
        galleryImage.src = image.src // image path
        console.log(image.src);

        /*
         * Create EXIF portion of LightBox
         */
        let galleryExif = document.createElement('p');
        galleryExif.classList.add('galleryExif');
        galleryExif.textContent = image.getAttribute('data-exif');

        /*
         * Create Text portion of Lightbox
         */
        let nextSibling = image.nextElementSibling; // Grab the next sibling:
        let galleryText = document.createElement('p');
        galleryText.classList.add('galleryText');
        //console.log(nextSibling.textContent);
        //galleryText.textContent = nextSibling.textContent;


        /* Remove Image For Screen (cleanup) */
        while (lightbox.firstChild) {
            lightbox.removeChild(lightbox.firstChild)
        }

        /* Add Image to Screen */
        lightbox.appendChild(galleryImage);

        /* Add EXIF to Screen */
        lightbox.appendChild(galleryExif);

        /* Add Content to Screen */
        //lightbox.appendChild(galleryText);


    })

})

lightbox.addEventListener('click', () => {
    if (lightbox.hasChildNodes()) {
        lightbox.classList.remove('active'); // Exit Lightbox by removing active css class
        lightbox.classList.add('lightbox');
        document.querySelector('.content').style.display = 'grid';
        //document.querySelector('.pagination').style.display = 'flex';
    }
})