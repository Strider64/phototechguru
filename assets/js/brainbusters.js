
// Declaring global variables that are needed throughout the code
let index, triviaData, choice, score = 0;

// Accessing DOM elements using their IDs
const resultElement = document.querySelector("#result");
const scoreElement = document.querySelector("#score");
const answerButtons = [
    document.querySelector("#ans1"),
    document.querySelector("#ans2"),
    document.querySelector("#ans3"),
    document.querySelector("#ans4")
];

// Function to enable all the answer buttons
const enableButtons = () => {
    answerButtons.forEach(button => {
        button.disabled = false;
    });
};

// Function to disable all the answer buttons
const disableButtons = () => {
    answerButtons.forEach(button => {
        button.disabled = true;
    });
};

const nextButton = document.querySelector("#next");
nextButton.style.display = "none"; // initially hide the next button

// Adding an event listener to the nextButton. This function is called when the next button is clicked.
nextButton.addEventListener("click", () => {
    index++;
    if (index < triviaData.length) {
        startGame(triviaData[index]); // Start the next question
        nextButton.style.display = "none";
        resultElement.textContent = "";
        enableButtons(); // Enable all the answer buttons for the next question
    } else {
        console.log("Game over"); // If there are no more questions, end the game.
        disableButtons(); //Disable Buttons
        nextButton.style.display = 'none';
        // Remove the event listener when the game is over
        nextButton.removeEventListener("click", nextButtonHandler);
    }

});

// Function to handle the event when an answer is selected.
const pickAnswer = (answerIndex) => {
    return () => {
        choice = answerIndex;
        checkAnswer();
        // Only show the next button if the next question exists
        if (index < triviaData.length - 1) {
            nextButton.style.display = "block"; // show the next button after an answer is chosen
        }
    };
};


const retrieveCorrectAnswer = (id) => {
    // Send a POST request to the server to check the correct answer
    fetch('fetch_correct_answer.php', {
        method: 'POST',
        body: JSON.stringify({ id: id })
    })
        // Handle errors
        .then((response) => handleErrors(response))
        // If the request is successful, compare the answers
        .then(data => checkAnswerAgainstTable(data))
        // If there is an error, log the error to the console
        .catch(error => error);
};


const checkAnswer = () => {
    disableButtons(); // Disable all the answer buttons after an answer is chosen
    const id = document
        .querySelector("#currentQuestion")
        .getAttribute("data-record");

    retrieveCorrectAnswer(id);
};

// Function to check if the selected answer is correct and update the score accordingly
const checkAnswerAgainstTable = (data) => {
    const correctAnswer = data.correct;

    if (correctAnswer === choice) {
        resultElement.textContent = "Correct!";
        resultElement.style.color = "green";
        score++; // Increment the score if the answer is correct
        scoreElement.textContent = `${score}`;
    } else {
        resultElement.textContent = "Incorrect. The correct answer was: " + correctAnswer;
        resultElement.style.color = "red";
    }
};


// Function to start a question by updating the question and answers in the UI
const startGame = ({ ans1, ans2, ans3, ans4, id, question }) => {
    document.querySelector("#currentQuestion").setAttribute("data-record", id);
    document.querySelector("#currentQuestion").textContent = (
        index + 1
    ).toString();
    document.querySelector("#question").textContent = question;

    answerButtons.forEach((button, index) => {
        const previousPickAnswer = button.__pickAnswer__;
        if (previousPickAnswer) {
            button.removeEventListener("click", previousPickAnswer);
        }

        const newPickAnswer = pickAnswer(index + 1);
        button.addEventListener("click", newPickAnswer, false);
        button.__pickAnswer__ = newPickAnswer;

        button.textContent = `📷 ${[ans1, ans2, ans3, ans4][index]}` || "";
        if (![ans1, ans2, ans3, ans4][index]) {
            button.style.pointerEvents = "none";
        } else {
            button.style.pointerEvents = "auto";
        }
    });
};

const resetGame = () => {
    choice = 0;
    score = 0;
    resultElement.textContent = "";
    scoreElement.textContent = `${score}`;
}

// Function to initialize the trivia game
const initializeTrivia = (data) => {
    index = 0;
    triviaData = data;
    startGame(triviaData[index]); // Start the first question
};

// Function to handle errors in the fetch response
const handleErrors = (response) => {
    if (!response.ok) {
        throw response.status + " : " + response.statusText;
    }
    return response.json();
};

// Function to fetch the trivia questions and answers
const fetchTiviaQuestionsAnswers = async (url) => {
    const response = await fetch(url);
    const data = await handleErrors(response);
    initializeTrivia(data); // Initialize the trivia game after fetching the questions and answers
};

const categorySelect = document.querySelector("#category");
const mainGame = document.querySelector("#mainGame");

categorySelect.addEventListener("change", () => {
    const selectedCategory = categorySelect.value;
    resetGame();
    enableButtons();
    if (selectedCategory) {
        mainGame.style.display = "block";
        // Now call your trivia question fetching function
        fetchTiviaQuestionsAnswers(`fetch_questions.php?category=${selectedCategory}`);
    } else {
        mainGame.style.display = "none";
    }
});
