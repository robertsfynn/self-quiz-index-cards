<?php
/**
 * Created by PhpStorm.
 * User: Fynn-Laptop
 * Date: 03.02.2018
 * Time: 17:45
 */

$title="Edit Quiz";


require_once '../database/config.php';
require('../layout/header.php');

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: login.php");
    exit;
}

$quizID = processText($_GET['id']);
$quizname = $questions = $answers =  "";
$sql = "SELECT quizName, questions, answers FROM quiz WHERE id={$_SESSION["id"]} AND quizID='$quizID'";
$result = mysqli_query($link, $sql);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $quizname = $row["quizName"];
    $questions = $row["questions"];
    $answers =  $row["answers"];
} else {
    echo "Soemthing went wrong";
    header("location: index.php");
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
        $sql2 = "UPDATE quiz SET quizName='$quizname' , questions='$questions', answers='$answers' WHERE quizID='$quizID' AND id={$_SESSION["id"]}";
        if (mysqli_query($link, $sql2)) {
            header("location: quizzes.php");
            exit;
        } else {
            echo "Error updating record: " . mysqli_error($link);
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

include("../layout/footer.php")


<script>
    $(function () {

        // Target all classed with ".lined"
        $(".lined").linedtextarea(
            {selectedLine: 1}
        );

    });
</script>