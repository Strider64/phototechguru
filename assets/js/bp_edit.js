'use strict';

/*
 *  The Blood Pressure Tracking ver 1.8  using FETCH/JSON
 *  by John R. Pepp
 *  Started: April 14, 2022
 *  Revised: Aprile 18 @ 7:00 pm
 */

(function () {
    let e_data = {};

    /* Handle General Errors in Fetch */
    const handleErrors = function (response) {
        if (!response.ok) {
            throw (response.status + ' : ' + response.statusText);
        }
        return response.json();
    };

    /* Save to Databse Table */
    const updateUISuccess = function (info) {
        console.log(info);

    };


    /* If Database Table fails to save data in mysql table */
    const updateUIError = function (error) {
        console.log("Database Table did not load", error);
    };

    /* Save the data to the Blood Pressure table */
    const updateFile = (retrieveUrl, succeed, fail) => {
        console.log(JSON.stringify(e_data));
        fetch(retrieveUrl, {
            method: 'POST', // or 'PUT'
            body: JSON.stringify(e_data)
        })
            .then((response) => handleErrors(response))
            .then((data) => succeed(data))
            .catch((error) => fail(error));
    };

    const changeText = (e) => {

        if (e.target.classList.contains('date_taken')) {
            //console.log(e.target.getAttribute('data-id'), e.target.valueOf().textContent);
            e_data.id = parseInt(e.target.getAttribute('data-id'));
            e_data.date_taken = e.target.valueOf().textContent;
            console.log('data', e_data);
            updateFile('bp_edit.php', updateUISuccess, updateUIError)
        }
        if (e.target.classList.contains('systolic')) {
            e_data.id = parseInt(e.target.getAttribute('data-id'));
            e_data.systolic = parseInt(e.target.valueOf().textContent);
            console.log('data', e_data);
            updateFile('bp_edit.php', updateUISuccess, updateUIError)
        }
    };
    document.addEventListener( 'focusout', changeText, false);

})();