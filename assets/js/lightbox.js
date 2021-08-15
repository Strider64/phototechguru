const lightbox = document.createElement('div')
lightbox.id = 'lightbox';

document.body.appendChild(lightbox);

const images = document.querySelectorAll('img')
images.forEach(image => {
    image.addEventListener('click', () => {
        lightbox.classList.add('active')
        const divBox = document.createElement('div');
        divBox.classList.add('boxStyle')
        const img = document.createElement('img');
        img.classList.add('imageStyle')
        const exif = document.createElement('p');
        exif.textContent = image.getAttribute('data-exif');
        exif.classList.add('displayInfo');
        console.log(exif);
        img.src = image.src

        while (lightbox.firstChild) {
            lightbox.removeChild(lightbox.firstChild)
        }
        lightbox.appendChild(divBox);
        divBox.appendChild(img);
        divBox.appendChild(exif);
    })
})

lightbox.addEventListener('click', () => {
    if (lightbox.hasChildNodes()) {
        lightbox.classList.remove('active');
    }
})