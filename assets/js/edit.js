(() => {
    // Select DOM elements
    const ePrev = document.querySelector('#ePrev');
    const status = document.querySelector('#status');
    const position = document.querySelector('#position');
    const eNext = document.querySelector('#eNext');
    const submitBtn = document.querySelector('#submit_button');
    const form = document.querySelector('#editTrivia');
    const deleteRecord = document.querySelector('#delete_quiz_record');

    // Set up variables
    let tableIndex = 0;
    let totalRecords = 0;
    let records = null;

    // Set up event listeners
    eNext.addEventListener('click', forward);
    ePrev.addEventListener('click', reverse);
    submitBtn.addEventListener('click', sendToTable);

    // Retrieve data from server and initialize UI
    retrieveTable();

    // Define event handlers
    function forward(e) {
        e.preventDefault();
        tableIndex = (tableIndex + 1) % totalRecords;
        updateUI();
    }

    function reverse(e) {
        e.preventDefault();
        tableIndex = (tableIndex - 1 + totalRecords) % totalRecords;
        updateUI();
    }

    // Fetches the table data from the server and sets up the UI
    function retrieveTable() {
        // Fetch the table data from the server
        fetch('retrieve_record.php')
            .then(response => response.json())
            .then(parsedData => {
                // Store the parsed data in the 'records' array and update the UI
                totalRecords = parsedData.length;
                records = parsedData;
                updateUI();
                //deleteRec();
            })
            .catch(error => console.log('Database table did not load', error));
    }
    function deleteRec() {
        const record = records[tableIndex];
        console.log(record.id)
        document.querySelector('#delete_quiz_record').setAttribute('formaction', `delete_quiz_record.php?id=${record.id}`);
    }
// Updates the UI with the currently selected record
    function updateUI() {

        // Get the current record from the 'records' array
        const record = records[tableIndex];
        position.textContent = record.id;
        // Update the form fields with the current record's data
        form.elements.id.value = record.id;
        form.elements.user_id.value = record.user_id;
        form.elements.hidden.value = record.hidden;
        form.elements.question.value = record.question;
        form.elements.ans1.value = record.ans1;
        form.elements.ans2.value = record.ans2;
        form.elements.ans3.value = record.ans3;
        form.elements.ans4.value = record.ans4;
        form.elements.correct.value = record.correct;
        form.elements.category.value = record.category;
    }

// Sends the current form data to the server to be saved
    function sendToTable(e) {
        e.preventDefault();

        // Create a FormData object from the form and convert it to an array
        const formData = new FormData(form);
        const dataArray = Array.from(formData.entries());

        // Convert the array of key/value pairs to an object with properties based on the field names
        const dataObject = dataArray.reduce((obj, [key, value]) => {
            if (key === 'id' || key === 'user_id' || key === 'correct') {
                obj[key] = parseInt(value); // Convert 'id', 'user_id', and 'correct' fields to integers
            } else {
                obj[key] = value;
            }
            return obj;
        }, {});

        // Send the data to the server using fetch
        const saveUrl = 'save_record.php';
        fetch(saveUrl, { method: 'POST', body: JSON.stringify(dataObject) })
            .then(response => response.json())
            .then(() => {
                // If the save was successful, update the UI to show a success message
                status.style.color = '#45A049';
                // Refresh the table data from the server
                retrieveTable();
                setTimeout(() => { status.style.color = '#2e2e2e'; }, 4000);
            })
            .catch(error => console.log('Database table did not save', error));
    }

})();
