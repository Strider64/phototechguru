<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";
use PhotoTech\Database;

$pdo = Database::pdo(); // PDO Connection:


$stmt = $pdo->query("SELECT * FROM demo_state ORDER BY name");
$records = $stmt->fetchAll();

//echo "<pre>" . print_r($records, 1) . "</pre>";
//die();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>State-City Example</title>
</head>
<body>
<form action="" method="post">
    <select name="stateId" id="stateId">
        <option selected disabled>--Select State--</option>
        <?php
            foreach ($records as $record) {
                echo '<option value="' . $record['id'] . '">' . $record['name'] . '</option>';
            }
        ?>
    </select>
</form>
<script>
    // PHP file of Fetch Statement
    let cityUrl = 'getCities.php?';
    // Grab the selector element in the DOM
    let stateId = document.querySelector('#stateId');

    /* Handle General Errors in Fetch */
    const handleErrors = function (response) {
        if (!response.ok) {
            throw (response.status + ' : ' + response.statusText);
        }
        return response.json();
    };

    /* Success function utilizing FETCH */
    const cityUISuccess = (parsedData) => {
        data = parsedData;
        console.log(data[1].name);
    }

    /* Oops, something went wrong */
    const cityUIError = (error) => {
        console.log("Database Table did not load", error);
    }

    /* create FETCH request */
    const createRequest = (url, succeed, fail) => {
        fetch(url)
            .then((response) => handleErrors(response))
            .then((data) => succeed(data))
            .catch((error) => fail(error));
    };

    // Send id of State Function
    function selection(id) {
        //console.log(id);
        const requestUrl = `${cityUrl}id=${id}`;
        //console.log(requestUrl);
        /*
         * Send URL of getcites, cityUISuccess FCN and cityError FCN to FETCH
         */
        createRequest(requestUrl, cityUISuccess, cityUIError);
    }
    // Add an Event Listener to see if user has selected the state (country);
    stateId.addEventListener('change', () => { selection(stateId.value) }, false);
</script>
</body>
</html>

