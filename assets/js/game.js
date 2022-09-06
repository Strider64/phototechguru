/*
 *  The Trivia Quiz 6.90 using FETCH/JSON
 *  by John R. Pepp
 *  Started: January 14, 2020
 *  Revised: February 14, 2022 @ 8:00 am
 */

'use strict';
(function () {
    /*
     * Constants & Variables Initialization Section.
     */

    const quizUrl = 'trivia_database_table.php?'; // PHP database script
    const d = document; // Shorten document function::
    d.querySelector('#photography');
    d.querySelector('.gameTitle');
    const buttonContainer = d.querySelector('#buttonContainer');
    const question = d.querySelector('#question');
    const next = d.querySelector('#next');
    const points = 100;
    const scoreText = d.querySelector('#score');
    let percent = d.querySelector('#percent');
    const dSec = 20; // Countdown Clock for questions:
    let failed = false;
    let gameIndex = 0,
        myRed = '#ff0000',
        gameData = null, // Array of Objects (id, questions and answers):
        timer = null,
        score = 0,
        total = 0,
        answeredRight = 0,
        answeredWrong = 0,
        totalQuestions = 0,
        shotsRemaining = 5,
        username = d.querySelector('.displayMessage').getAttribute('data-username'),
        finalResult = d.querySelector('#finalResult'),
        highScoresDisplay = d.querySelector('.addTriviaInfo'),
        hs_table = {};

    let responseAns = {};
    setGaugeValue(gaugeElement, shotsRemaining / 5);
    finalResult.style.display = "none";
    highScoresDisplay.style.display = "none";
    //const buttons = d.querySelectorAll(".answerButton");
    const mainGame = d.querySelector('#mainGame');
    next.style.display = "none";

    /*
     * Start and Stop Functions for Countdown Timer For Trivia Game
     */
    const startTimer = (dSec) => {
        let seconds = dSec;
        const userAnswer = 5, correct = 1;
        const newClock = d.querySelector('#clock');

        const currentQuestion = d.querySelector('#currentQuestion');

        currentQuestion.textContent = String(gameIndex + 1);


        newClock.style['color'] = '#2e2e2e';
        newClock.textContent = ((seconds < 10) ? `0${seconds}` : seconds);
        const countdown = () => {
            if (seconds === 0) {
                clearTimeout(timer);
                newClock.style['color'] = myRed;
                newClock.textContent = "00";
                if (shotsRemaining < 1) {
                    shotsRemaining = shotsRemaining - 1;
                    setGaugeValue(gaugeElement, shotsRemaining / 5);
                }
                scoringFcn(userAnswer, correct);
                highlightFCN(userAnswer, correct);
                calcPercent(answeredRight, total);
                disableListeners();
                if ((gameIndex + 1) === totalQuestions) {

                    next.textContent = 'results';
                }

                next.style.display = "block";
                next.addEventListener('click', removeQuiz, false);
            } else {
                newClock.textContent = ((seconds < 10) ? `0${seconds}` : seconds);
                seconds--;
            }
        };
        timer = setInterval(countdown, 1000);
    };

    const stopTimer = () => {
        clearInterval(timer);
    };

    /* Highlight correct or wrong answers */
    const highlightFCN = (userAnswer, correct) => {
        const highlights = d.querySelectorAll('.answerButton');
        highlights.forEach(answer => {

            /*
             * Highlight Answers Function
             * User answered correctly
             */
            if (userAnswer === correct && userAnswer === parseInt(answer.getAttribute('data-correct'))) {

                answer.textContent = answer.textContent.substring(2);
                answer.textContent = "📸 " + answer.textContent;
                answer.style.background = 'green';
                answer.style.color = 'white';
            }

            /*
             * User answered incorrectly
             */
            if (userAnswer !== correct && userAnswer === parseInt(answer.getAttribute('data-correct'))) {
                answer.textContent = answer.textContent.substring(2);
                answer.textContent = "😥 " + answer.textContent;
                answer.style.background = 'red';
                answer.style.color = 'white';
            }
            if (userAnswer !== correct && correct === parseInt(answer.getAttribute('data-correct'))) {
                answer.textContent = answer.textContent.substring(2);
                answer.textContent = "📸 " + answer.textContent;
                answer.style.background = 'green';
                answer.style.color = 'white';
            }

            /*
             * User let timer run out
             */
            if (userAnswer === 5) {
                answer.textContent = answer.textContent.substring(2);
                answer.textContent = "😥 " + answer.textContent;
            }
        });
    };

    /* Disable Listeners, so users can't click on answer buttons */
    const disableListeners = () => {
        const myButtons = d.querySelectorAll('.answerButton');
        myButtons.forEach(answer => {
            answer.removeEventListener('click', clickHandler, false);
        });
    };

    /* Calculate Percent */
    const calcPercent = (correct, total) => {
        let average = (correct / total) * 100;
        percent.textContent = average.toFixed(0) + "% Correct";
    };

    /* Figure out Score */
    const scoringFcn = (userAnswer, correct) => {


        if (userAnswer === correct) {
            score += points;
            answeredRight++;
            scoreText.textContent = `${score} Points`;
        } else {
            score = score - (points / 2);
            shotsRemaining = shotsRemaining - 1;
            setGaugeValue(gaugeElement, shotsRemaining / 5);
            answeredWrong++;
            scoreText.textContent = `${score} Points`;
        }
        total++;
    };

    /* Handle General Errors in Fetch */
    const handleErrors = function (response) {
        if (!response.ok) {
            throw (response.status + ' : ' + response.statusText);
        }
        return response.json();
    };

    /* Success function utilizing FETCH */
    const checkUISuccess = function (parsedData) {
        if (failed !== true) {
            let correct = parseInt(parsedData.correct);
            let userAnswer = parseInt(d.querySelector('#headerStyle').getAttribute('data-user'));
            scoringFcn(userAnswer, correct);
            calcPercent(answeredRight, total);
            highlightFCN(userAnswer, correct);

            disableListeners();
            next.style.display = "block";
            next.addEventListener('click', removeQuiz, false);
        }
    };

    /* If Database Table fails to load then hard code the correct answers */
    const checkUIError = function (error) {

        console.log("Database Table did not load", error);
        failed = true;
        let userAnswer = parseInt(d.querySelector('#headerStyle').getAttribute('data-user'));
        let response = [1, 4];
        let x = parseInt(gameData[gameIndex].id) - 1;

        let correct = response[x];
        scoringFcn(userAnswer, correct);
        calcPercent(answeredRight, total);
        highlightFCN(userAnswer, correct);

        disableListeners();
        next.style.display = "block";
        next.addEventListener('click', removeQuiz, false);

    };

    /* create FETCH request for check answers */
    const checkRequest = function (url, succeed, fail) {
        fetch(url, {
            method: 'POST', // or 'PUT'
            body: JSON.stringify(responseAns)

        })
            .then((response) => handleErrors(response))
            .then((data) => succeed(data))
            .catch((error) => fail(error));
    };

    /* User has made selection */
    const clickHandler = (e) => {
        let userAnswer = parseInt(e.target.getAttribute('data-correct'));
        responseAns.id = parseInt(gameData[gameIndex].id); // { id: integer }
        console.log(responseAns);
        const checkUrl = "check.php";
        stopTimer();
        if ((gameIndex + 1) === totalQuestions) {
            next.textContent = 'results';
        }
        checkRequest(checkUrl, checkUISuccess, checkUIError);
        d.querySelector('#headerStyle').setAttribute('data-user', userAnswer.toString());
    };

    /* Remove answers from Screen */
    const removeAnswers = () => {
        let element = d.querySelector('#buttonContainer');
        while (element.firstChild) {
            element.removeChild(element.firstChild);
        }
    };

    /* Clear High Score  & remove HS Table */
    const removeHighScores = () => {
        let element = d.querySelector('.anchor');
        while (element.firstChild) {
            element.removeChild(element.firstChild);
        }

    }

    /*
     * Create and Display High Score Table
     */
    const displayHSTable = (info) => {
        highScoresDisplay.style.display = "block";
        info.forEach((value, index) => {
            let anchor = d.querySelector('.anchor');
            let trElement = anchor.appendChild(d.createElement('tr'));
            if ((index + 1) % 2 === 0) {
                trElement.className = 'active-row';
            }
            let tdPlayer = trElement.appendChild(d.createElement('td'));
            let tdPoints = trElement.appendChild(d.createElement('td'));
            tdPlayer.appendChild(d.createTextNode(value.player));
            tdPoints.appendChild(d.createTextNode(value.score));
        });
    }

    /* Save User Data to hs_table */
    const saveHSTableSuccess = (info) => {

        if (info) {
            removeHighScores();
            createHSTable('retrieveHighScore.php', retrieveHSTableUISuccess, retrieveHSTableUIError);
        }

    };

    /* If Database Table fails to save data in mysql table */
    const saveHSTableUIError = function (error) {
        console.log("Database Table did not load", error);
    };


    /* create FETCH request */
    const saveHSTableRequest = (saveUrl, succeed, fail) => {
        fetch(saveUrl, {
            method: 'POST', // or 'PUT'
            body: JSON.stringify(hs_table)
        })
            .then((response) => handleErrors(response))
            .then((data) => succeed(data))
            .catch((error) => fail(error));
    };

    /* retrieve User Data from hs_table */
    const retrieveHSTableUISuccess = function (info) {
        displayHSTable(info);

    };

    /* If Database Table fails to save data in mysql table */
    const retrieveHSTableUIError = function (error) {
        console.log("Database Table did not load", error);
    };

    /* Create High Score Data using fetch */
    const createHSTable = (retrieveUrl, succeed, fail) => {

        let max = 5; // Maximum Records to Be Displayed
        let maximum = {};
        maximum.max_limit = max;

        fetch(retrieveUrl, {
            method: 'POST', // or 'PUT'
            body: JSON.stringify(maximum)
        })
            .then((response) => handleErrors(response))
            .then((data) => succeed(data))
            .catch((error) => fail(error));
    };

    createHSTable('retrieveHighScore.php', retrieveHSTableUISuccess, retrieveHSTableUIError);


    /*
     * Current Online Score of Player
     */
    const scoreboard = () => {

        const hideGame = d.querySelector('#quiz');
        hideGame.style.display = "none";
        finalResult.style.display = "block";
        d.querySelector('#scoreboard').style.display = "table";
        d.querySelector('.totalScore').textContent = score;

        d.querySelector('.answeredRight').textContent = answeredRight.toString();
        //d.querySelector('.totalQuestions').textContent = totalQuestions;
        hs_table.player = username;
        hs_table.score = score;
        hs_table.correct = answeredRight;
        hs_table.totalQuestions = totalQuestions;
        question.textContent = 'Game Over';
        saveHSTableRequest('hs_table.php', saveHSTableSuccess, saveHSTableUIError);
    }
    /* Remove Question & Answers */
    const removeQuiz = () => {
        removeAnswers(); // Call removeAnswers FCN:
        next.style.display = "none";
        next.removeEventListener('click', removeQuiz, false);
        gameIndex++;
        window.scrollTo(0, 0);

        if (gameIndex < totalQuestions && shotsRemaining > 0) {
            createQuiz(gameData[gameIndex]); // Recreate the Quiz Display:
        } else {
            scoreboard();
        }
    };

    /* Populate Question, Create Answer Buttons */
    const createQuiz = (gameData) => {

        startTimer(dSec);

        question.textContent = gameData.question;

        /*
         * Create Buttons then insert answers into buttons that were
         * create.
         */
        gameData.answers.forEach((value, index) => {


            let gameButton = buttonContainer.appendChild(d.createElement('button'));
            gameButton.id = 'answer' + (index + 1);
            gameButton.className = 'answerButton';
            gameButton.setAttribute('data-correct', (index + 1).toString());
            gameButton.addEventListener('click', clickHandler, false);
            /*
             * Don't Show Answers that have a Blank Field
             */
            if (value !== "") {
                gameButton.appendChild(d.createTextNode("📷 " + value));
            } else {
                gameButton.appendChild(d.createTextNode(" "));
                gameButton.style.pointerEvents = "none"; // Disable Click on Empty Field
            }
        });
    };

    /* Success function utilizing FETCH */
    const quizUISuccess = (parsedData) => {
        console.log('trivia data', parsedData);
        mainGame.style.display = 'grid';
        d.getElementById('content').scrollIntoView();

        //gameData = parsedData;


        gameData = parsedData.sort(() => Math.random() - .5); // randomize questions:
        totalQuestions = parseInt(gameData.length);
        createQuiz(gameData[gameIndex]);

    };

    /* If Database Table fails to load then answer a few hard coded Q&A */
    const quizUIError = (error) => {
        /*
         * If game database table fails to load then give a few questions
         * and answers, so that the game will still work properly.
         */
        failed = true;
        mainGame.style.display = 'grid';
        d.getElementById('content').scrollIntoView();
        console.log("Database Table did not load", error);
        gameData = [{
            "id": 1,
            "user_id": 1,
            "hidden": "no",
            "question": "[Blank] is the length of time when the film or digital sensor inside the camera is exposed to light, also when a camera's shutter is open when taking a photograph. The amount of light that reaches the film or image sensor is proportional to the [Blank].",
            "category": "photography",
            "answers": [
                "Shutter Speed or Exposure Time",
                "ISO",
                "",
                ""
            ]
        },
            {
                "id": 2,
                "user_id": 1,
                "hidden": "no",
                "question": "[Blank] was one of the earliest photographers in American history, best known for his scenes of the Civil War. He studied under inventor Samuel F. B. Morse, who pioneered the daguerreotype technique in America. [Blank] opened his own studio in New York in 1844, and photographed Andrew Jackson, John Quincy Adams, and Abraham Lincoln, among other public figures.",
                "category": "photography",
                "answers": [
                    "Robert Capa",
                    "Steve McCurry",
                    "Ansel Adams",
                    "Matthew Brady"
                ]
            }
        ]
        totalQuestions = gameData.length;
        //console.log(gameData[gameIndex]);
        createQuiz(gameData[gameIndex]);
    };

    /* create FETCH request */
    const createRequest = (url, succeed, fail) => {
        fetch(url)
            .then((response) => handleErrors(response))
            .then((data) => succeed(data))
            .catch((error) => fail(error));
    };

    /*
     * Start Game by Category
     */
    const selectCat = function (category) {

        const requestUrl = `${quizUrl}category=${category}`;

        createRequest(requestUrl, quizUISuccess, quizUIError);

    };


    //d.querySelector('.main').scrollIntoView();
    //selectCat('photography');
    const startGame = (e) => {
        e.preventDefault();
        selectCat('photography');
        d.querySelector('.displayStatus').style.display = 'none';
        d.querySelector('#customBtn').style.display = 'none';
        d.querySelector('#quiz').style.display = 'block';
    };

    //d.querySelector('#customBtn').addEventListener('click', startGame, false);
    //d.querySelector('#quiz').style.display = "none";

    function selection(category) {

        console.log('category', category);
        d.querySelector('#quiz').style.display = "none";
        selectCat(category);
        d.querySelector('.displayStatus').style.display = 'none';

        d.querySelector('#quiz').style.display = 'block';
    }
    d.querySelector('#quiz').style.display = 'none';
    let category = d.querySelector('#category');
    category.addEventListener('change', () => { selection(category.value) } , false);
    //console.log('category', category.value);

})();