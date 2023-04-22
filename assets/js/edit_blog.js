'use strict';
//edit_blog.js
(function () {
    document.addEventListener("DOMContentLoaded", function () {
        // Get references to the search form and edit form elements
        const searchForm = document.getElementById("searchForm");
        const editForm = document.getElementById("data_entry_form");

        // Get references to the input elements for ID, question, and answer
        const idInput = document.getElementById("id");
        const image_for_edit_record = document.getElementById("image_for_edited_record");
        const category = document.getElementById("category");
        const heading = document.getElementById("heading");
        const content = document.getElementById("content");
        const resultInput = document.getElementById("searchTerm");
        // Define an asynchronous function to display a record based on a search term
        async function displayRecord(searchTerm) {
            try {
                // Send a POST request to the search_records.php endpoint with the search term
                const response = await fetch("search_blog_records.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({searchTerm: searchTerm}),
                });

                // Get the response as JSON
                const data = await response.json();
                if (data.message) {
                    resultInput.value = '';          // Clear the current value of the input field
                    resultInput.placeholder = data.message; // Set the placeholder attribute to data.message

                } else if (data.error) {
                    console.error(data.error);
                } else {
                    // If data is found, display the first row in the console and update the input fields
                    const row = data[0];
                    //console.log("Record", row);
                    idInput.value = row.id;
                    image_for_edit_record.src = row.thumb_path;
                    image_for_edit_record.alt = row.heading;
                    category.value = row.category;
                    category.textContent = `${row.category.charAt(0).toUpperCase()}${row.category.slice(1)}`;
                    heading.value = row.heading;
                    content.value = row.content;

                }
            } catch (error) {
                // Log any errors that occur during the request
                console.error("Error:", error);
            }
        }

        // Add an event listener to the search form's submit event
        searchForm.addEventListener("submit", function (event) {
            // Prevent the default form submit behavior
            event.preventDefault();
            // Get the value of the search term input field
            const searchTerm = document.getElementById("searchTerm").value;
            // Call the displayRecord function with the search term
            displayRecord(searchTerm);
        });

        // Add an event listener to the edit form's submit event
        editForm.addEventListener("submit", async function (event) {
            // Prevent the default form submit behavior
            event.preventDefault();

            // Create a FormData object from the edit form
            const formData = new FormData(editForm);
            //console.log("form data", formData);
            // Send a POST request to the update_question.php endpoint with the form data
            const response = await fetch("edit_update_blog.php", {
                method: "POST",
                body: formData,
            });

            // Check if the request was successful
            if (response.ok) {
                const result = await response.json();
                console.log(result);
                // If the response has a "success" property and its value is true, clear the form
                if (result.success) {
                    resultInput.value = '';          // Clear the current value of the input field
                    resultInput.placeholder = "New Search"; // Set the placeholder to `New Search`
                    image_for_edit_record.src = "";
                    image_for_edit_record.alt = "";
                    editForm.reset(); // Updated line: call reset() method on the form element
                }
            } else {
                console.error(
                    "Error submitting the form:",
                    response.status,
                    response.statusText
                );
                // Handle error response
            }
        });


    });
})();