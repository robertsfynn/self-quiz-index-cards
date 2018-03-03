<?php
/**
 * Created by PhpStorm.
 * User: Fynne
 * Date: 29.01.2018
 * Time: 10:11
 */
$title = 'Quiz';

require_once '../database/config.php';
require('../layout/header.php');


if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: login.php");
    exit;
}
// get quizID from url
$quizID = processText($_GET['id']);
$sql = "SELECT quizName, questions, answers FROM quiz WHERE id={$_SESSION["id"]} AND quizID='$quizID'";

if (!$result = mysqli_query($link, $sql)) {
    echo "Something went wrong, try again later!";
    header("location: quizzes.php");
    exit;
}

$quizName = $questions = $answers = "";

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $quizName = $row["quizName"];
    $questions = $row["questions"];
    $answers = $row["answers"];
}
?>
<div class="container">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title text-center"><?php echo $quizName ?></h2>
            <p id="fileDisplayArea" class="text-center" style="font-size:20px; margin-top: 20px"></p>
            <div id="answerButton" class="text-center"
                 style="font-size:20px; margin-top: 20px; margin-bottom: 20px"></div>
            <p id="answerDisplayArea" class="text-center" style="font-size:20px; margin-top: 20px"></p>
            <div id="button" class="text-center"></div>
        </div>
    </div>
</div>
<?php
include("../layout/footer.php")
?>

<script type="text/javascript">
    var questions = <?php echo json_encode($questions); ?>;
    var answers = <?php echo json_encode($answers); ?>;
    var splitQuestions;
    var splitAnswers;
    var randomNumber;
    var display;
    var counter = 0;
    var wrongQuestions = [];
    var wrongAnswers = [];
    var questionCounter;

    function getRandomInt(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min)) + min;
    }

    function removeQuestions() {
        splitQuestions.splice(randomNumber, 1);
        splitAnswers.splice(randomNumber, 1);
    }

    function wrong() {
        $("#answerButton").hide();
        $("#answerDisplayArea").hide();
        wrongQuestions[counter] = display;
        wrongAnswers[counter] = answerDisplayArea.innerText;
        counter++;
        randomNumber = getRandomInt(0, splitQuestions.length);
        display = splitQuestions[randomNumber];

        if (splitAnswers[randomNumber] && /\S/.test(splitAnswers[randomNumber])) {
            answerDisplayArea.innerText = splitAnswers[randomNumber];
            $("#answerButton").show();

        }

        if (display == undefined) {
            display = "Questions with incorrect Answers: \n \n"
            for (var i = 0; i < wrongQuestions.length; i++) {
                display += wrongQuestions[i] + "\n" + wrongAnswers[i] + "\n";
            }
            $("#button").hide();
        }

        fileDisplayArea.innerText = display;

        removeQuestions();
    }

    function right() {
        $("#answerButton").hide();
        $("#answerDisplayArea").hide();
        randomNumber = getRandomInt(0, splitQuestions.length);
        display = splitQuestions[randomNumber];
        if (splitAnswers[randomNumber] && /\S/.test(splitAnswers[randomNumber])) {
            answerDisplayArea.innerText = splitAnswers[randomNumber];
            $("#answerButton").show();
        }


        if (display === undefined) {
            if (wrongQuestions.length > 0) {
                display = "Questions with incorrect Answers: \n \n"
                for (var i = 0; i < wrongQuestions.length; i++) {
                    display += wrongQuestions[i] + "\n" + wrongAnswers[i] + "\n";
                }
            } else {
                display = "Everything correct!"
            }
            $("#button").hide();

        }


        fileDisplayArea.innerText = display;
        
        removeQuestions();
    }

    function answer() {

        $("#answerDisplayArea").show();
    }

    window.onload = function () {
        splitQuestions = questions.split(/\r?\n/);
        if (answers) {
            splitAnswers = answers.split("\n");
        } else {
            console.log("else")
            splitAnswers = [];
            splitAnswers.push("");
        }
        randomNumber = getRandomInt(0, splitQuestions.length)
        fileDisplayArea.innerText = splitQuestions[randomNumber];
        answerDisplayArea.innerText = splitAnswers[randomNumber];
        $("#answerDisplayArea").hide();

        display = fileDisplayArea.innerText;

        $("#button").append(" <button class='btn btn-dark' onclick='right()'>Correct</button> ");
        $("#button").append(" <button class='btn btn-dark' onclick='wrong()'>Incorrect</button> ");
        $("#answerButton").append(" <button class='btn btn-dark' onclick='answer()'>Answer</button> ");

        //Check if string has whitespace or is empty
        if (/\S/.test(splitAnswers[randomNumber]) === false || splitAnswers[randomNumber] === undefined) {
            $("#answerButton").hide();
        }
        
        removeQuestions();
    }


</script>