'use strict';
(function () {
    let data = {};
    data.per_page = 2;
    data.current_page = 1;

    const recordCard = function (record) {

        const date = new Date(record.date_added);

        const display_date = date.toLocaleString('en-US', {
            weekday: 'short', // "Sat"
            month: 'long', // "June"
            day: '2-digit', // "01"
            year: 'numeric' // "2019"
        });

        /* Get DIV with the class "container" */
        const container = document.querySelector('.container');

        /* Create HTML element article */
        const article = document.createElement('article');

        /* Create the img, h2, span and p HTML elements */
        const img = document.createElement('img'); // image element:
        const h2 = document.createElement('h2'); // h2 element:
        const span = document.createElement('span'); // span element:
        const p = document.createElement('p');

        /* Append the article element to the div container element */
        container.append(article);

        /* Append the img, h2, span and p HTML elements to the article element */
        article.append(img);
        article.append(h2);
        article.append(span);
        article.append(p);

        /* Add class or id, attributes and fill in text content to the HTML elements */
        article.classList.add('cms');

        img.classList.add('article_image');
        img.setAttribute('src', record.image_path);
        img.setAttribute('width', '800');
        img.setAttribute('height', '533');

        h2.textContent = record.heading;

        span.classList.add('author_style');
        span.textContent = 'Create_by ' + record.author + ' on ' + display_date;

        p.textContent = record.content;


    }

    /* Handle General Errors in Fetch */
    const handleErrors = function (response) {
        if (!response.ok) {
            throw (response.status + ' : ' + response.statusText);
        }
        return response.json();
    };

    /* Success function utilizing FETCH */
    const recordsUISuccess = function (records) {

        //console.log(records)

        /* Grabbing the record data from the Database Table
         * and assigning the value of the objects to the
         * HTML table using a forEach statement in
         * Vanilla JavaScript.
         */
        records.forEach(record => recordCard(record));
    };

    /* If Database Table fails to load then hard code the correct answers */
    const recordsUIError = function (error) {
        console.log("The Darn Database Table did not load", error);
    };

    /* create FETCH request retrieving records */
    const fetch_records = function (url, succeed, fail) {
        fetch(url, {
            method: 'POST', // or 'PUT'
            body: JSON.stringify(data)

        })
            .then((response) => handleErrors(response))
            .then((data) => succeed(data))
            .catch((error) => fail(error));
    };

    fetch_records('cms_with_pagination.php', recordsUISuccess, recordsUIError);

})();