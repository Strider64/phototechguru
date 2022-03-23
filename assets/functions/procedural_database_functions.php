<?php
/*
 * As long as you have the correct field names as the key and
 * the correct values in the corresponding keys the following
 * procedural function should work with no problem.
 *
 */


function insertData(array $data, $pdo, $table) {
    try {
        /* Initialize an array */
        $attribute_pairs = [];

        /*
         * Set up the query using prepared states with the values of the array matching
         * the corresponding keys in the array
         * and the array keys being the prepared named placeholders.
         */
        $sql = 'INSERT INTO ' . $table . ' (' . implode(", ", array_keys($data)) . ')';
        $sql .= ' VALUES ( :' . implode(', :', array_keys($data)) . ')';

        /*
         * Prepare the Database Table:
         */
        $stmt = $pdo->prepare($sql);

        /*
         * Grab the corresponding values in order to
         * insert them into the table when the script
         * is executed.
         */
        foreach ($data as $key => $value)
        {
            if($key === 'id') { continue; } // Don't include the id:
            $attribute_pairs[] = $value; // Assign it to an array:
        }

        return $stmt->execute($attribute_pairs); // Execute and send boolean true:

    } catch (PDOException $e) {

        /*
         * echo "unique index" . $e->errorInfo[1] . "<br>";
         *
         * An error has occurred if the error number is for something that
         * this code is designed to handle, i.e. a duplicate index, handle it
         * by telling the user what was wrong with the data they submitted
         * failure due to a specific error number that can be recovered
         * from by the visitor submitting a different value
         *
         * return false;
         *
         * else the error is for something else, either due to a
         * programming mistake or not validating input data properly,
         * that the visitor cannot do anything about or needs to know about
         *
         * throw $e;
         *
         * re-throw the exception and let the next higher exception
         * handler, php in this case, catch and handle it
         */

        if ($e->errorInfo[1] === 1062) {
            return false;
        }

        throw $e;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n"; // Not for a production server:
    }

    return true;
}

function updateData(array $data, $pdo, $table): bool
{
    /* Initialize an array */
    $attribute_pairs = [];

    /* Create the prepared statement string */
    foreach ($data as $key => $value)
    {
        if($key === 'id') { continue; } // Don't include the id:
        $attribute_pairs[] = "$key=:$key"; // Assign it to an array:
    }

    /*
     * The sql implodes the prepared statement array in the proper format
     * and updates the correct record by id.
     */
    $sql  = 'UPDATE ' . $table . ' SET ';
    $sql .= implode(", ", $attribute_pairs) . ' WHERE id =:id';

    /* Normally in two lines, but you can daisy-chain pdo method calls */
    $pdo->prepare($sql)->execute($data);

    return true;
}


