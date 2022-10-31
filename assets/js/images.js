'use strict';
(function () {
    let d = document;
    let category = d.querySelector('#category');
    let container = d.querySelector('.container');
    let sidebar = d.querySelector('.sidebar_pages');
    let current_page = 1, per_page =6, offset = 0;
    let database_data = {'category':'general', 'current_page': current_page, 'per_page': per_page, 'total_count': 0, 'offset': offset };
    let pages = [{}];
    let total_pages = 0;

    /* Handle General Errors in Fetch */
    const handleErrors = function (response) {
        if (!response.ok) {
            throw (response.status + ' : ' + response.statusText);
        }
        return response.json();
    };

    /*
     * FETCH for New Category
     */
    const categoryUISuccess = (parsedData) => {
        //console.log(parsedData, database_data.total_count);
        /* Remove Image For Screen (cleanup) */
        while (container.firstChild) {
            container.removeChild(container.firstChild)
        }

        let count = 0; // For different class names for size boxes in CSS

        parsedData.forEach(slide => {
            /* Main Image Slide Block */
            let displayDiv = d.createElement('div');
            /* Array of different size class names for CSS */
            let displayFormat = ["gallery-container w-3 h-2", 'gallery-container w-3 h-2',
                                 'gallery-container w-3 h-2', 'gallery-container w-3 h-2',
                                 'gallery-container w-3 h-2', 'gallery-container w-3 h-2'];
            displayDiv.className = `${displayFormat[count]}`; //Assign Class Names to Div:
            container.appendChild(displayDiv); //Append Child to Parent Div:

            /*
             * Create div for indiviual images
             */
            let galleryItem = d.createElement('div');
            galleryItem.classList.add('gallery-item');
            displayDiv.appendChild(galleryItem);
            /*
             * Image div element
             */
            let images = d.createElement('div');
            images.classList.add('images');
            galleryItem.appendChild(images);
            /*
             * Image itself
             */
            let galleryImage = d.createElement('img')
            //console.log(slide.image_path);
            galleryImage.src = slide.image_path;
            galleryImage.setAttribute('alt', slide.content); // Description of what image is about:
            /* Set EXIF info to data-exif attribute */
            galleryImage.setAttribute('data-exif', slide.Model + ' ' + slide.ExposureTime + ' ' + slide.Aperture + ' '
                + slide.ISO + ' ' + slide.FocalLength);
            images.appendChild(galleryImage); // Append image to Image div element:
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

        /*
         * Create Lightbox for Large Image Display
         */
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
                //console.log(image.src);

                /*
                 * Create EXIF portion of LightBox
                 */
                let galleryExif = document.createElement('p');
                galleryExif.classList.add('galleryExif');
                galleryExif.textContent = image.getAttribute('data-exif');

                /*
                 * Create Text portion of Lightbox
                 */

                //let nextSibling = image.nextElementSibling; // Grab the next sibling:
                //let galleryText = document.createElement('p');
                //galleryText.classList.add('galleryText');
                //console.log(nextSibling.textContent);
                //galleryText.textContent = nextSibling.textContent;

                /* Remove large Image For Screen (cleanup) */
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

    const paginationUISuccess = (parsedData) => {

        /* Remove Links For Screen (cleanup) */
        while (sidebar.firstChild) {
            sidebar.removeChild(sidebar.firstChild)
        }
        database_data.total_count = parsedData.total_count; // Total Pages of Category
        database_data.offset = parsedData.offset;
        total_pages = Math.ceil(database_data.total_count/database_data.per_page);

        /* Create the Display Links and add an event listener */
        pages = [{}];
        /*
         * Creating the array of page object(s)
         */
        for (let x = 0; x < total_pages; x++) {
            pages[x] = {page: x+1};
        }

        pages.forEach(link_page => {
            const links = d.createElement('div');
            links.className = 'links';
            sidebar.appendChild(links);
            /*
             * Add event listener for the links
             */
            links.addEventListener('click', () => {
                database_data.current_page = link_page.page;
                createRequest('galleryPagination.php', paginationUISuccess, paginationUIError);
            });
            const pageText = d.createElement('p');
            pageText.className = 'linkStyle';
            pageText.id = 'page_' + link_page.page;
            pageText.textContent = link_page.page;
            links.appendChild(pageText);
            if (database_data.current_page === link_page.page) {
                links.style.backgroundColor = "#00b28d";
            }
        })

        createRequest('galleryImagesGet.php', categoryUISuccess, categoryUIError);

    };

    const paginationUIError = (error) => {
        console.log("Database Table did not load", error);
    };

    /* create FETCH request */
    const createRequest = (url, succeed, fail) => {
        fetch(url, {
            method: 'POST', // or 'PUT'
            body: JSON.stringify(database_data)
        })
            .then((response) => handleErrors(response))
            .then((data) => succeed(data))
            .catch((error) => fail(error));
    };

    /* Display the first page of the gallery */
    createRequest('galleryPagination.php', paginationUISuccess, paginationUIError);

    /*
     * Create an event listener to allow the user to change categories
     */
    category.addEventListener('change', () => {
        database_data.current_page = 1; // When changing category change current page to 1:
        database_data.category = category.value;
        createRequest('galleryPagination.php', paginationUISuccess, paginationUIError);

    }, false);




})();