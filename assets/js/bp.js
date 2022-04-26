'use strict';

/*
 *  The Blood Pressure Tracking ver 1.8  using FETCH/JSON
 *  by John R. Pepp
 *  Started: April 14, 2022
 *  Revised: Aprile 18 @ 7:00 pm
 */

let total_count = parseInt(document.querySelector('.site').getAttribute('data-pages'));
let per_page = 14;
let current_page = 0;
let total_pages = Math.ceil(total_count / per_page);

let offset = per_page * current_page;
let data = {
    current: current_page,
    total_count: total_count,
    total_pages: total_pages,
    per_page: per_page,
    offset: offset
};
//console.log(data);


/* Handle General Errors in Fetch */
const handleErrors = function (response) {
    if (!response.ok) {
        throw (response.status + ' : ' + response.statusText);
    }
    return response.json();
};
const formatDate = (timestamp) => {

    // Create a date object from the timestamp
    let date = new Date(timestamp);

    // Create a list of names for the months
    let months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    // return a formatted date
    return months[date.getMonth()] + ' ' + date.getDate().toString().padStart(2, '0') + ', ' + date.getFullYear();

};

/* Remove data from Screen */
const clear_entries = () => {
    let element = document.querySelector('.cards');
    while (element.firstChild) {
        element.removeChild(element.firstChild);
    }
    let flex_cont = document.querySelector('.flex_container');
    while (flex_cont.firstChild) {
        flex_cont.removeChild(flex_cont.firstChild);
    }
};

/*
 * Action when user click on one of those anchor tags
 */
const callPage = (e) => {
    e.preventDefault(); // prevent anchor tag from firing
    data.current = parseInt(e.target.getAttribute('data-page')); // Determine Current Page:
    data.offset = per_page * data.current; // Calculate offset:
    clear_entries(); // Clear the Screen of Old Data:
    /* Retrieve Next or Previous Page Data */
    createTable('newRetrieveBP.php', retrieveBPTableUISuccess, retrieveBPTableUIError);
}

/* Create the Links for Pagination (Still needs improvement) */
const links = () => {

    let min = 1;
    let max = total_pages;
    const range = (min, max) => [...Array(max - min + 1).keys()].map(i => i + min);
    const flex_container = document.querySelector('.flex_container');
    /* Make a ranges of the pages */
    let pages = range(min, max);
    /*
     * Create anchor element, css classes and add event listener.
     */
    pages.forEach((value, index) => {
        //console.log('value', value, 'index', index, 'data_current', data.current);
        let page = flex_container.appendChild(document.createElement('a'));
        page.className = "flex-item";
        if (data.current === index) {
            page.className = 'selected';
        }

        page.setAttribute('data-page', index);
        page.setAttribute('href', '');
        page.addEventListener('click', callPage, false);
        page.appendChild(document.createTextNode(value));

    });

};

/* A function to process the records and display the on the screen */
const process_record = (records) => {
    //console.log('records', records);
    let entries = document.querySelector('.cards');

    records.forEach(record => {
        let card = document.createElement('li');
        let anchor = document.createElement('a');
        anchor.classList.add('anchor');
        anchor.setAttribute('href', '#');
        let bp_readings = document.createElement('div');
        bp_readings.classList.add('bp_readings');
        let other_measures = document.createElement('div');
        other_measures.classList.add('other_measures');

        let date_taken = document.createElement('p');
        let systolic = document.createElement('p');
        let diastolic = document.createElement('p');
        let pulse = document.createElement('p');


        let miles_walked = document.createElement('p');
        let weight = document.createElement('p');
        let sodium = document.createElement('p');

        card.classList.add('card');

        date_taken.classList.add('date_taken');
        date_taken.textContent = formatDate(record.date_taken);
        date_taken.setAttribute('contenteditable', 'true');
        date_taken.setAttribute('data-id', record.id);

        systolic.classList.add('systolic');
        systolic.textContent = record.systolic;
        systolic.setAttribute('contenteditable', 'true');
        systolic.setAttribute('data-id', record.id);

        diastolic.classList.add('diastolic');
        diastolic.textContent = record.diastolic;
        diastolic.setAttribute('contenteditable', 'true');
        diastolic.setAttribute('data-id', record.id);

        pulse.classList.add('pulse');
        pulse.textContent = record.pulse;
        pulse.setAttribute('contenteditable', 'true');
        pulse.setAttribute('data-id', record.id);

        miles_walked.classList.add('miles');
        if (record.miles_walked == 0) {
            record.miles_walked = null;
        }
        miles_walked.setAttribute('contenteditable', 'true');
        miles_walked.textContent = record.miles_walked;
        miles_walked.setAttribute('data-id', record.id);

        weight.classList.add('weight');
        if (record.weight == 0) {
            record.weight = null;
        }
        weight.setAttribute('contenteditable', 'true');
        weight.textContent = record.weight;
        weight.setAttribute('data-id', record.id);

        sodium.classList.add('sodium');
        if (record.sodium == 0) {
            record.sodium = null;
        }
        sodium.setAttribute('contenteditable', 'true');
        sodium.textContent = record.sodium;
        sodium.setAttribute('data-id', record.id);

        entries.appendChild(card);
        card.appendChild(anchor);
        anchor.appendChild(date_taken);
        anchor.appendChild(bp_readings);


        bp_readings.appendChild(systolic)
        bp_readings.appendChild(diastolic);
        bp_readings.appendChild(pulse);

        anchor.appendChild(other_measures);
        other_measures.appendChild(miles_walked);
        other_measures.appendChild(weight);
        other_measures.appendChild(sodium);


    });

}

/* retrieve User Data and Create Links */
const retrieveBPTableUISuccess = function (bp) {
    //console.log(bp);
    process_record(bp);
    links();

};

/* If Database Table fails to save data in mysql table */
const retrieveBPTableUIError = function (error) {
    console.log("Database Table did not load", error);
};

/* show Data for Blood Pressure */
const createTable = (retrieveUrl, succeed, fail) => {
    fetch(retrieveUrl, {
        method: 'POST', // or 'PUT'
        body: JSON.stringify(data)
    })
        .then((response) => handleErrors(response))
        .then((data) => succeed(data))
        .catch((error) => fail(error));
};

/* Retrieve the Data and Display them */
createTable('newRetrieveBP.php', retrieveBPTableUISuccess, retrieveBPTableUIError);



