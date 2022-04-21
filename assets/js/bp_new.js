'use strict';

/*
 *  The Blood Pressure Tracking ver 1.8  using FETCH/JSON
 *  by John R. Pepp
 *  Started: April 14, 2022
 *  Revised: Aprile 18 @ 7:00 pm
 */

(function () {
    let data = {};

    let myButton = document.querySelector('.bpButton');

    /* Handle General Errors in Fetch */
    const handleErrors = function (response) {
        if (!response.ok) {
            throw (response.status + ' : ' + response.statusText);
        }
        return response.json();
    };

    /* Save to Databse Table */
    const saveToFileUISuccess = function (info) {
        console.log(info);
        clear_entries(); // clear the entries so there are duplicates:
        /* Read read the Data in order to get the new entry */
        createTable('newRetrieveBP.php', retrieveBPTableUISuccess, retrieveBPTableUIError);
    };


    /* If Database Table fails to save data in mysql table */
    const saveToFileUIError = function (error) {
        console.log("Database Table did not load", error);
    };

    /* Save the data to the Blood Pressure table */
    const saveToFile = (retrieveUrl, succeed, fail) => {
        document.getElementById('bpForm').reset();
        fetch(retrieveUrl, {
            method: 'POST', // or 'PUT'
            body: JSON.stringify(data)
        })
            .then((response) => handleErrors(response))
            .then((data) => succeed(data))
            .catch((error) => fail(error));
    };

    /* Insert User's Entry From Form into a data object */
    const saveData = (e) => {
        e.preventDefault();
        data.date_taken = document.querySelector('#date_taken_form').value;
        data.date_taken.toString();
        data.systolic = parseInt(document.querySelector('#systolic_form').value);
        data.diastolic = parseInt(document.querySelector('#diastolic_form').value);
        data.pulse = parseInt(document.querySelector('#pulse_form').value);
        data.miles_walked = parseFloat(document.querySelector('#miles_walked_form').value);
        data.weight = parseInt(document.querySelector('#weight_form').value);
        data.sodium = parseInt(document.querySelector('#sodium_form').value);
        //console.log('Data', data);
        saveToFile('saveBPData.php', saveToFileUISuccess, saveToFileUIError);


   };

    /* Add an event listener, so when the user clicks the button it saves the data */
    myButton.addEventListener('click',  saveData, false);

})();