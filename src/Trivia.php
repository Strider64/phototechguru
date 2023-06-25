<?php /** @noinspection ALL */

namespace PhotoTech;

use PDO;

class Trivia
{

    public $id;
    public $user_id;
    public $hidden;
    public $question;
    public $answer1;
    public $answer2;
    public $answer3;
    public $answer4;
    public $correct;
    public $category;
    public $play_date;
    public $day_of_week;
    public $day_of_year;

    protected PDO $pdo;
    protected $params;
    protected $objects;

    public function __construct(PDO $pdo, $args = [])
    {
        $this->pdo = $pdo;

        foreach ($args as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
                $this->params[$k] = $v;
                $this->objects[] = $v;
            }
        }
    }

    public function fetch_data($searchTerm): array
    {
        static::$searchItem = 'category';
        static::$searchValue = $searchTerm;
        $sql = "SELECT id, user_id, hidden, question, answer1, answer2, answer3, answer4, category FROM trivia_questions WHERE category=:category";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([ $this->searchItem => $this->searchValue ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /*
     * Grab all the columns from table in order
     * to edit:
     */
    public function fetch_all_data($searchTerm): array
    {
        static::$searchItem = 'category';
        static::$searchValue = $searchTerm;
        $sql = "SELECT * FROM trivia_questions WHERE category=:category";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([ $this->searchItem => $this->searchValue ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function fetch_all_categories(): array
    {
        $sql = "SELECT * FROM trivia_questions";
        // execute a query
        $statement = $this->pdo->query($sql);

        // fetch all rows
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * Fetch correct answer:
     */
    public function fetch_correct_answer($searchTerm):array
    {
        static::$searchItem = 'id';
        static::$searchValue = $searchTerm;
        $sql = "SELECT id, correct FROM trivia_questions WHERE id=:id";
        $stmt = $this->pdo->prepare($sql); // Database::pdo() is the PDO Connection

        $stmt->execute([ $this->searchItem => $thi->searchValue ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(): bool
    {
        /* Initialize an array */
        $attribute_pairs = [];

        /*
         * Set up the query using prepared statements with named placeholders.
         */
        $sql = 'INSERT INTO trivia_questions (' . implode(", ", array_keys($this->params)) . ')';
        $sql .= ' VALUES ( :' . implode(', :', array_keys($this->params)) . ')';

        /*
         * Prepare the Database Table:
         */
        $stmt = $this->pdo->prepare($sql); // PHP Version 8.x Database::pdo()

        /*
         * Bind the corresponding values in order to
         * insert them into the table when the script
         * is executed.
         */
        foreach ($this->params as $key => $value) {
            if ($key === 'id') {
                continue; // Don't include the id
            }
            $stmt->bindValue(':' . $key, $value); // Bind values to the named placeholders
        }

        // Execute the statement and return true if successful, otherwise false
        return $stmt->execute();
    }

    public function update(): bool
    {
        /* Initialize an array */
        $attribute_pairs = [];

        /* Create the prepared statement string */
        foreach (static::$params as $key => $value)
        {
            if($key === 'id') { continue; } // Don't include the id:
            $attribute_pairs[] = "{$key}=:{$key}"; // Assign it to an array:
        }

        /*
         * The query/sql implodes the prepared statement array in the proper format
         * and I also hard code the date_updated column as I practically use that for
         * all my database table. Though I think you could override that in the child
         * class if you needed too.
         */
        $sql  = 'UPDATE ' . static::$table . ' SET ';
        $sql .= implode(", ", $attribute_pairs) . ' WHERE id =:id';

        /* Normally in two lines, but you can daisy chain pdo method calls */
        $this->pdo->prepare($sql)->execute(static::$params);

        return true;

    }

    public function readHighScores($maximum) {
        $query = 'SELECT * FROM hs_table ORDER BY score DESC LIMIT :maximum';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['maximum' => (int) $maximum['max_limit']]);
        $output = $stmt->fetchAll();

        return $output;
    }

    public function insertHighScores($data) {
        $query = 'INSERT INTO hs_table( player, score, played, correct, totalQuestions, day_of_year ) VALUES ( :player, :score, NOW(), :correct, :totalQuestions, :day_of_year )';
        $stmt = $this->pdo->prepare($query);
        $result = $stmt->execute(['player' => $data['player'], 'score' => $data['score'], 'correct' => $data['correct'], 'totalQuestions' => $data['totalQuestions'], 'day_of_year' => $data['day_of_year']]);
        return $result;
    }

    public function clearTable() {


        $sql = "DELETE FROM hs_table WHERE played < CURDATE()";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute();
    }

}