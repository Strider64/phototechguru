const lightbox = document.createElement('div')
lightbox.id = 'lightbox'
document.body.appendChild(lightbox)

const images = document.querySelectorAll('img')
images.forEach(image => {
    image.addEventListener('click', () => {
        lightbox.classList.add('active')
        const img = document.createElement('img')
        const exif = document.createElement('p');
        exif.textContent = image.getAttribute('data-exif');
        exif.classList.add('displayInfo');
        console.log(exif);
        img.src = image.src
        while (lightbox.firstChild) {
            lightbox.removeChild(lightbox.firstChild)
        }
        lightbox.appendChild(img);
        lightbox.appendChild(exif);
    })
})

lightbox.addEventListener('click', e => {
    if (e.target !== e.currentTarget) return
    lightbox.classList.remove('active')
})