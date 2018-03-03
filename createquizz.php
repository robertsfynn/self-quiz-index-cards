<?php
$title="Create Quiz";

require_once '../database/config.php';
require('../layout/header.php');
/**
 *
 * Created by PhpStorm.
 * User: Fynn-Laptop
 * Date: 27.01.2018
 * Time: 19:28
 */
$quizname = $questions = "";
$quizname_err = $questions_err = "";


if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(processText($_POST["quizname"]))) {
        $quizname_err = "Please enter a quizname.";
    } else {
        $quizname = processText($_POST["quizname"]);
    }

    if (empty(processText($_POST["questions"]))) {
        $questions_err = "Please enter questions.";
    } else {
        $questions = processText($_POST[("questions")]);
        $answers = $_POST[("answers")];
    }

    if (empty($questions_err) && empty($quizname_err)) {
        $sql = "INSERT INTO quiz (quizName, questions, answers ,id) VALUES ('$quizname', '$questions','$answers  ' , '{$_SESSION['id']}')";
        if (mysqli_query($link, $sql)) {
            header("location: quizzes.php");
            exit;
        } else {
            echo "Something went wrong, try again later!";
            header("location: quizzes.php");
            exit;
        }
    }

};

?>
<div class="container">
    <div class="card">
        <div class="card-body">
            <form action="" method="post">
                <div class="form-group <?php echo (!empty($quizname_err)) ? 'has-error' : ''; ?>">
                    <label>Quiz Name:</label>
                    <input type="text" name="quizname" class="form-control" value="<?php echo $quizname; ?>">
                    <span class="help-block"><?php echo $quizname_err; ?></span>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12 col-md-12 <?php echo (!empty(questions_err)) ? 'has-error' : ''; ?>">
                        <label for="">Questions:</label>
                        <textarea class="form-control lined" rows="20" name="questions"><?php echo $questions ?></textarea>
                        <span class="help-block"><?php echo $questions_err; ?></span>
                    </div>
                    <div class="form-group col-sm-12 col-md-12">
                        <label for="">Answers:</label>
                        <textarea class="form-control lined" rows="20" name="answers"><?php echo $answers ?></textarea>
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-dark" value="Submit">
                </div>
            </form>
        </div>
    </div>
</div>
<?php
include("../layout/footer.php")

?>
<script>
    $(function () {

        // Target all classed with ".lined"
        $(".lined").linedtextarea(
            {selectedLine: 1}
        );

    });
</script>