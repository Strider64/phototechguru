"use strict";
(function () {
    let data = {};
    data.per_page = 2;
    data.current_page = 0;

    const linkCard = function (l_record, index) {
        console.log('l_record', l_record, 'index', index);
        const links_container = document.querySelector(".links_container");

        /* Create HTML elements article */
        const previous = document.createElement("div");
        const prev_p = document.createElement('p');

        const link1 = document.createElement("div");
        const link1_p = document.createElement('p');

        const link2 = document.createElement("div");
        const link2_p = document.createElement('p');

        const link3 = document.createElement("div");
        const link3_p = document.createElement('p');

        const link4 = document.createElement("div");
        const link4_p = document.createElement('p');

        const link5 = document.createElement("div");
        const link5_p = document.createElement('p');

        const next = document.createElement("div");
        const next_p = document.createElement('p');

        /* Append the link elements to the div container element */
        links_container.append(previous);
        previous.classList.add("previous");
        previous.append(prev_p);
        prev_p.textContent = 'Previous';

        links_container.append(link1);
        link1.classList.add("link1");
        link1.append(link1_p);
        link1_p.textContent = index;


        links_container.append(link2);
        link2.classList.add("link2");
        link2.append(link2_p);
        link2_p.textContent = index + 1;

        links_container.append(link3);
        link3.classList.add("link3");
        link3.append(link3_p);
        link3_p.textContent = index + 2;

        links_container.append(link4);
        link4.classList.add("link4");
        link4.append(link4_p);
        link4_p.textContent = index + 3;

        links_container.append(link5);
        link5.classList.add("link5");
        link5.append(link5_p);
        link5_p.textContent = index + 4;

        links_container.append(next);
        next.classList.add("next");
        next.append(next_p);
        next_p.textContent = 'Next';
    };


    /* Handle General Errors in Fetch */
    const handleLinkErrors = function (response) {
        if (!response.ok) {
            throw (response.status + ' : ' + response.statusText);
        }
        return response.json();
    };

    /* Success function utilizing FETCH */
    const linksUISuccess = function (record) {


        //records.forEach((record, index) => linkCard(record, index));
        //console.log('link record', records)

        //console.log('record', record)
        linkCard(record, 1);
    };

    /* If Database Table fails to load then hard code the correct answers */
    const linksUIError = function (error) {
        console.log("The Darn Database Table did not load", error);
    };

    /* create FETCH request retrieving records */
    const fetch_links = function (url, succeed, fail) {
        fetch(url, {
            method: 'POST', // or 'PUT'
            body: JSON.stringify(data)

        })
            .then((response) => handleLinkErrors(response))
            .then((data) => succeed(data))
            .catch((error) => fail(error));
    };
    fetch_links('links.php', linksUISuccess, linksUIError);

})();