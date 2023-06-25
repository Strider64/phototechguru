'use strict';

(() => {
    let quiz = document.querySelector('#quiz');
    let opening = document.querySelector('#openingScreen');
    //let quiz = document.querySelector('#quiz');
    //quiz.style.visibility = 'hidden';
    let category = document.querySelectorAll('a.category');
    /* Da Question */
    let q = document.querySelector('#question');
    /* Answer Buttons */
    let answer1 = document.querySelector('#ans1');
    let answer2 = document.querySelector('#ans2');
    let answer3 = document.querySelector('#ans3');
    let answer4 = document.querySelector('#ans4');
    let choice = 0;
    /* Setup, Disable and Hide the next button */
    let next = document.querySelector('#next');
    next.style.pointerEvents = 'none';
    next.style.visibility = 'hidden';

    let dsec = 25; // Seconds
    let name = 'Guest Player';
    const points = 100;
    let percent = document.querySelector('#percent');
    let score = 0;
    let total = 1;
    let answeredRight = 0;
    let highScoresDisplay = document.querySelector('.addTriviaInfo');
    let scoreboard = document.querySelector('#score');
    let gameColor = 'green';

    let lego = document.querySelector('#lego');
    let photography = document.querySelector('#photography');
    let space = document.querySelector('#space');
    let movie = document.querySelector('#movie');
    let sport = document.querySelector('#sport');
    let username = document.querySelector('.displayMessage').getAttribute('data-username');
    let finalResult = document.querySelector('#finalResult');
    //let blueBackground = "#8eafed";
    let whiteColor = "#ffffff"
    let hs_table = {player: null};


    /* Calculate Percent */
    const calcPercent = (correct, total) => {
        hs_table.correct = correct;
        hs_table.total = total;
        let average = (correct / total) * 100;
        percent.textContent = average.toFixed(0) + "% Correct";
    };

    let timer = null;





    const adjectives = ["Fast", "Fierce", "Mighty", "Swift", "Stealthy", "Cunning", "Wise", "Brave"];
    const nouns = ["Warrior", "Knight", "Hero", "Champion", "Assassin", "Wizard", "Barbarian", "Samurai"];

    function generatePlayerName(username) {
        if (username === 'guest') {
            const randomAdjective = adjectives[Math.floor(Math.random() * adjectives.length)];
            const randomNoun = nouns[Math.floor(Math.random() * nouns.length)];
            return randomAdjective + " " + randomNoun;
        }

        return username;

    }

    hs_table.player = generatePlayerName(username);

    console.log(hs_table.player); // output: "Fierce Hero" (or any other combination of adjective and noun)

    highScoresDisplay.style.display = "none";

    let index = 0;
    let questionNumber = 1;
    let totalRecords = 0;
    let triviaData = null;

    finalResult.style.display = 'none';

    /* Clear High Score  & remove HS Table */
    const removeHighScores = () => {
        const anchor = document.querySelector('.anchor');
        while (anchor.firstChild) {
            anchor.removeChild(anchor.firstChild);
        }
    }

    // Create and Display High Score Table
    const displayHSTable = (info) => {
        highScoresDisplay.style.display = "block";

        info.forEach(({player, score}, index) => {
            const anchor = document.querySelector('.anchor');
            const rowClass = (index + 1) % 2 === 0 ? 'active-row' : '';

            const trElement = document.createElement('tr');
            trElement.className = rowClass;
            trElement.innerHTML = `
            <td>${player}</td>
            <td>${score}</td>
        `;

            anchor.appendChild(trElement);
        });
    }


    /* Save User Data to hs_table */
    const saveHSTableSuccess = (info) => {

       console.log(info);
        removeHighScores();
        output_high_scores_to_screen(
            'read_high_scores.php',
            retrieveHSTableUISuccess,
            retrieveHSTableUIError
        );
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

    const output_high_scores_to_screen = (url, onSuccess, onError) => {
        const maxRecords = 0;
        const data = { max_limit: maxRecords };

        fetch(url, {
            method: 'POST',
            body: JSON.stringify(data)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Network response was not ok. Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => onSuccess(data))
            .catch(error => onError(error));
    };

    output_high_scores_to_screen(
        'read_high_scores.php',
        retrieveHSTableUISuccess,
        retrieveHSTableUIError
    );



    const resetButtons = (fontColor="white", backgroundColor="#8eafed") => {
        /* Reset Buttons to Original State */
        for (let i = 1; i <= 4; i++) {
            switch (i) {
                case 1:
                    answer1.style.pointerEvents = 'initial';
                    answer1.style.backgroundColor = backgroundColor;
                    answer1.style.color = fontColor;
                    break;
                case 2:
                    answer2.style.pointerEvents = 'initial';
                    answer2.style.backgroundColor = backgroundColor;
                    answer2.style.color = fontColor;
                    break;
                case 3:
                    answer3.style.pointerEvents = 'initial';
                    answer3.style.backgroundColor = backgroundColor;
                    answer3.style.color = fontColor;
                    break;
                case 4:
                    answer4.style.pointerEvents = 'initial';
                    answer4.style.backgroundColor = backgroundColor;
                    answer4.style.color = fontColor;
                    break;
                default:
                    console.log('Error......');
            }
        }
    }

    const nextQuestion = () => {
        next.style.pointerEvents = "none";
        next.style.visibility = 'hidden';
        /* Make it so user can't click on already answered question */
        resetButtons();
        /* Remove the addEventListener(s) */
        answer1.removeEventListener('click', pick1, false);
        answer2.removeEventListener('click', pick2, false);
        answer3.removeEventListener('click', pick3, false);
        answer4.removeEventListener('click', pick4, false);

        /* Display the Next Question or end the Game*/
        index++;
        questionNumber++;
        total = questionNumber;

        if (index < totalRecords) {
            startGame(triviaData[index]);
        } else {
            quiz.style.display = "none";
            opening.style.display = "block";
            triviaData = null;
            choice = null;
            /* Remove to disable class that prevents menu to be clicked */
            for (let elem of category) {
                elem.classList.remove('disable');
            }
            saveHSTableRequest('save_high_scores.php', saveHSTableSuccess, saveHSTableUIError);

            console.log(`End of Game!`);
        }
    }

    /* Reset the Trivia Game to Original State */
    const setup = () => {
        disableButtons()
        next.style.pointerEvents = "initial";
        next.style.visibility = 'visible';
        /* Onward to the next question in the database table */
        next.addEventListener('click', nextQuestion, false);
        choice = 0; // Making sure user choice is cleared:

    }



    /* Highlight the HTML buttons after user has 'click' */
    const highlightColor = (result, gameColor) => {
        switch (result) {
            case 1:
                answer1["style"]["backgroundColor"] = gameColor;
                answer1['style']["color"] = "white";

                break;
            case 2:
                answer2["style"]["backgroundColor"] = gameColor;
                answer2['style']["color"] = "white";
                break;
            case 3:
                answer3["style"]["backgroundColor"] = gameColor;
                answer3['style']["color"] = "white";
                break;
            case 4:
                answer4["style"]["backgroundColor"] = gameColor;
                answer4['style']["color"] = "white";
                break;
            default:
                console.log("Error");
        }
    }

    const wrongFCN = (wrong) => {
        if (wrong === 1) {
            answer1.textContent =  answer1.textContent.substring(2);
            answer1.textContent = "😥 " + answer1.textContent;
        }
        if (wrong === 2) {
            answer2.textContent =  answer2.textContent.substring(2);
            answer2.textContent = "😥 " + answer2.textContent;
        }
        if (wrong === 3) {
            answer3.textContent =  answer3.textContent.substring(2);
            answer3.textContent = "😥 " + answer3.textContent;
        }
        if (wrong === 4) {
            answer4.textContent = answer4.textContent.substring(2);
            answer4.textContent = "😥 " + answer4.textContent;
        }
    }

    const rightFCN = (correct) => {
        if (correct === 1) {
            answer1.textContent =  answer1.textContent.substring(2);
            answer1.textContent = "📸 " + answer1.textContent;
        }
        if (correct === 2) {
            answer2.textContent =  answer2.textContent.substring(2);
            answer2.textContent = "📸 " + answer2.textContent;
        }
        if (correct === 3) {
            answer3.textContent =  answer3.textContent.substring(2);
            answer3.textContent = "📸 " + answer3.textContent;
        }
        if (correct === 4) {
            answer4.textContent = answer4.textContent.substring(2);
            answer4.textContent = "📸 " + answer4.textContent;
        }
    }

    const enableButtons = () => {
        for (let i = 1; i <= 4; i++) {
            switch (i) {
                case 1:
                    answer1.style.pointerEvents = 'initial';
                    break;
                case 2:
                    answer2.style.pointerEvents = 'initial';
                    break;
                case 3:
                    answer3.style.pointerEvents = 'initial';
                    break;
                case 4:
                    answer4.style.pointerEvents = 'initial';
                    break;
                default:
                    console.log('Error......');
            }
        }
    }
    const startTimer = (duration) => {
        let seconds = duration;

        const newClock = document.querySelector('#clock');
        newClock.textContent = duration;
        newClock.style.color = '#4CAF50';

        const formatTime = (seconds) => ((seconds < 10) ? `0${seconds}` : seconds);

        const countdown = () => {
            if (seconds === 0) {
                clearTimeout(timer);
                newClock.style.color = 'red';
                newClock.textContent = "00";
                score -= 25;
                scoreboard.textContent = 'Points: ' + score;

                resetButtons('white', 'red');

                if ((index + 1) === totalRecords) {
                    console.log('End of Game');
                } else {
                    setup();
                }
            } else {
                newClock.textContent = formatTime(seconds);
                seconds--;
            }
        };
        timer = setInterval(countdown, 1000);
    };

    const stopTimer = () => {
        clearInterval(timer);
    };

    const disableButtons = () => {
        for (let i = 1; i <= 4; i++) {
            switch (i) {
                case 1:
                    answer1.style.pointerEvents = 'none';
                    break;
                case 2:
                    answer2.style.pointerEvents = 'none';
                    break;
                case 3:
                    answer3.style.pointerEvents = 'none';
                    break;
                case 4:
                    answer4.style.pointerEvents = 'none';
                    break;
                default:
                    console.log('Error......');
            }
        }
    }


    const checkAnswerAgainstTable = ({ correct }) => {
        // Check if the user's answer is correct
        if (correct === choice) {
            // If the answer is correct, add points to the score and update the scoreboard
            rightFCN(correct);
            score += points;
            answeredRight++;
            scoreboard.textContent = 'Points: ' + score;
            // Highlight the selected answer in green
            highlightColor(choice, gameColor);
        } else {
            // If the answer is incorrect, subtract points from the score and update the scoreboard
            score -= 25;
            scoreboard.textContent = 'Points: ' + score;
            // Highlight the correct answer in green and the selected answer in red
            highlightColor(correct, gameColor);
            wrongFCN(choice);
            rightFCN(correct);
            highlightColor(choice, 'red');
        }
        // Update the score in the high scores table
        hs_table.score = score;
        // Calculate the percentage of questions answered correctly and update the progress bar
        calcPercent(answeredRight, total);
        /* Make it so user can't click on already answered question */
        // Disable all answer buttons for the current question
        for (let i = 1; i <= 4; i++) {
            switch (i) {
                case 1:
                    answer1.style.pointerEvents = 'none';
                    break;
                case 2:
                    answer2.style.pointerEvents = 'none';
                    break;
                case 3:
                    answer3.style.pointerEvents = 'none';
                    break;
                case 4:
                    answer4.style.pointerEvents = 'none';
                    break;
                default:
                    console.log('Error......');
            }
        }
        // Move to the next question

        setup();
    };

    // Retrieve the correct answer from the database table
    const retrieveCorrectAnswer = (id) => {
        // Send a POST request to the server to check the correct answer
        fetch('checkAnswer.php', {
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
    
    const pick1 = () => {
        stopTimer();
        const id = document.querySelector("#currentQuestion").getAttribute("data-record");
        retrieveCorrectAnswer(id);
        // Set the choice selected
        choice = 1;
    };

    const pick2 = () => {
        stopTimer();
        const id = document.querySelector("#currentQuestion").getAttribute("data-record");
        retrieveCorrectAnswer(id);
        // Set the choice selected
        choice = 2;
    };

    const pick3 = () => {
        stopTimer();
        const id = document.querySelector("#currentQuestion").getAttribute("data-record");
        retrieveCorrectAnswer(id);
        // Set the choice selected
        choice = 3;
    };

    const pick4 = () => {
        stopTimer();
        const id = document.querySelector("#currentQuestion").getAttribute("data-record");
        retrieveCorrectAnswer(id);
        // Set the choice selected
        choice = 4;
    };

    const startGame = ({ ans1, ans2, ans3, ans4, id, question }) => {
        startTimer(25);
        quiz.style.display = "block";
        opening.style.display = "none";
        document.querySelector('.catHeading').style.display = 'none';
        document.getElementById('legoNav').style.display = 'none';
        // Disable menu click
        for (const elem of category) {
            elem.classList.add("disable");
        }
        resetButtons();

        // Set the current record (id) to the data-record attribute
        document.querySelector("#currentQuestion").setAttribute("data-record", id);
        document.querySelector("#currentQuestion").textContent = questionNumber.toString();

        // Display the questions and answers on the page
        q.textContent = question;

        // Add Listeners to Answers
        answer1.addEventListener("click", pick1, false);
        answer2.addEventListener("click", pick2, false);
        (ans3 !== "") ? answer3.addEventListener("click", pick3, false) : answer3.style.pointerEvents = 'none';
        (ans4 !== "") ? answer4.addEventListener("click", pick4, false) : answer4.style.pointerEvents = 'none';

        // Set Possible Answers
        answer1.textContent = `📷 ${ans1}`;
        answer2.textContent = `📷 ${ans2}`;
        answer3.textContent = ans3 ? `📷 ${ans3}` : "";
        answer4.textContent = ans4 ? `📷 ${ans4}` : "";

    };


    const initializeTrivia = (data) => {
        answeredRight = 0;
        total = 1;
        percent.textContent = "100% Correct";
        index = 0;
        choice = null;
        triviaData = null;
        triviaData = data;
        totalRecords = triviaData.length;
        hs_table.totalQuestions = totalRecords
        startGame(triviaData[index]);
    }

    /* Handle General Errors in Fetch */
    const handleErrors = function (response) {
        if (!response.ok) {
            throw (response.status + ' : ' + response.statusText);
        }
        return response.json();
    };

    const fetchTiviaQuestionsAnswers = async (url) => {
        resetButtons();
        enableButtons();
        const response = await fetch(url);
        const data = await handleErrors(response);
        initializeTrivia(data);
    };

    const categoryClick = (category) => {
        return (e) => {
            e.preventDefault();
            questionNumber = 1;
            score = 0;
            scoreboard.textContent = 'Points: ' + score;
            fetchTiviaQuestionsAnswers(`fetchQuestions.php?category=${category}`);
        }
    };

    lego.addEventListener('click', categoryClick('lego'), false);
    photography.addEventListener('click', categoryClick('photography'), false);
    space.addEventListener('click', categoryClick('space'), false);
    movie.addEventListener('click', categoryClick('movie'), false);
    sport.addEventListener('click', categoryClick('sport'), false);


})();