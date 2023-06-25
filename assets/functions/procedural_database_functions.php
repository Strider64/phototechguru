<?php
/*
 * As long as you have the correct field names as the key and
 * the correct values in the corresponding keys the following
 * procedural function should work with no problem.
 *
 */


function create(array $data, $pdo, $table) {
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


