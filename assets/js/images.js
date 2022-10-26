'use strict';
(function () {
    let d = document;
    let category = d.querySelector('#category');
    let container = d.querySelector('.container');





    /* Handle General Errors in Fetch */
    const handleErrors = function (response) {
        if (!response.ok) {
            throw (response.status + ' : ' + response.statusText);
        }
        return response.json();
    };

    const categoryUISuccess = (parsedData) => {
        console.log('data ', parsedData);

        let count = 0;

        parsedData.forEach(slide => {
            /* Main Image Slide Block */
            let displayDiv = d.createElement('div');
            //console.log('slide image_path', slide.image_path);
            let displayFormat = ["gallery-container w-4 h-2", 'gallery-container w-3 h-2', 'gallery-container w-4 h-2', 'gallery-container w-3 h-2', 'gallery-container w-3 h-2', 'gallery-container w-2 h-2', 'gallery-container h-2', 'gallery-container h-2', 'gallery-container w-2 h-2', 'gallery-container h-2', 'gallery-container w-2 h-2', 'gallery-container w-4 h-2'];
            displayDiv.className = `${displayFormat[count]}`;
            container.appendChild(displayDiv);


            let galleryItem = d.createElement('div');
            galleryItem.classList.add('gallery-item');
            displayDiv.appendChild(galleryItem);
            /*
             * Image Block
             */
            let images = d.createElement('div');
            images.classList.add('images');
            galleryItem.appendChild(images);
            /*
             * Image
             */
            let galleryImage = d.createElement('img')
            //console.log(slide.image_path);
            galleryImage.src = slide.image_path;
            galleryImage.setAttribute('alt', slide.content);

            galleryImage.setAttribute('data-exif', slide.Model + ' ' + slide.ExposureTime + ' ' + slide.Aperture + ' '
                + slide.ISO + ' ' + slide.FocalLength);
            images.appendChild(galleryImage);
            /*
             * Hidden Paragraph
             */
            let paragraph = d.createElement('p');
            paragraph.classList.add('hideContent');
            paragraph.textContent = slide.content;
            images.appendChild(paragraph);
            /*
             * Title Block
             */
            let title = d.createElement('div');
            title.classList.add('title');
            galleryItem.appendChild(title);
            /*
             * Heading 1
             */
            let heading1 = d.createElement('h1');
            heading1.classList.add('pictureHeading');
            heading1.textContent = `${slide.heading[0].toUpperCase()}${slide.heading.slice(1)}`;
            title.appendChild(heading1);
            let titleSpan = d.createElement('span');
            titleSpan.classList.add('exifInfo');
            titleSpan.textContent = slide.Model;
            title.appendChild(titleSpan);

            count += 1;
        })
        const lightbox = document.createElement('div')


        lightbox.classList.add('lightbox');

        document.body.appendChild(lightbox);
        const images = document.querySelectorAll('img')
        console.log('images', images);
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
    };

    const categoryUIError = (error) => {
        console.log("Database Table did not load", error);
    }

    /* create FETCH request */
    const createRequest = (url, succeed, fail) => {
        let cat = {}
        cat.category = category.value;
        console.log(category.value);
        console.log('cat',cat);
        fetch(url, {
            method: 'POST', // or 'PUT'
            body: JSON.stringify(cat)
        })
            .then((response) => handleErrors(response))
            //.then(res => res.text())          // convert to plain text
            //.then(text => console.log(text))
            .then((data) => succeed(data))
            .catch((error) => fail(error));
    };

    function selection() {
        /* Remove Image For Screen (cleanup) */
        while (container.firstChild) {
            container.removeChild(container.firstChild)
        }


        let url = 'galleryImagesGet.php';
        //let url =  urlRequest +  "category=" + category;

        createRequest(url, categoryUISuccess, categoryUIError);
    }
    category.addEventListener('change', () => { selection(category.value) } , false);
    category.value = 'general';
    createRequest("galleryImagesGet.php" , categoryUISuccess, categoryUIError);
})();