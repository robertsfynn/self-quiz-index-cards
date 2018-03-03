<?php
/**
 * Created by PhpStorm.
 * User: Fynn-Laptop
 * Date: 27.01.2018
 * Time: 18:11
 */
//define page title
$title = 'Quizzes';

require("../layout/header.php");
require_once("../database/config.php");
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: login.php");
    exit;
}


// ORDER BY to show the last made quiz first
$sql = "SELECT quizName, questions, quizID FROM quiz WHERE id={$_SESSION["id"]} ORDER BY quizID DESC";
$result = mysqli_query($link, $sql);


?>

<div class="container">
    <div class="card">
        <a href="createquizz.php" class="btn btn-dark">Create a Quizz</a>
    </div>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><img src="../img/magnify.png"></span>
        </div>
        <input class="form-control glyphicon glyphicon-search" type="text" id="myInput" onkeyup="searchQuiz()"
               placeholder="Search..">
    </div>
    <div class="card-body">
        <div class="row" id="row">
            <?php if (mysqli_num_rows($result) > 0) {

                // output data of each row into Bootstrap card
                while ($row = mysqli_fetch_assoc($result)):
                    //Put line break after every question mark
                    $questions = str_replace(['{', '}'], ["\r\n}", "}\r\n"], $row["questions"]);
                    $quizID = $row["quizID"];
                    $quizName = $row["quizName"]; ?>

                    <div class="card col-sm-12 col-md-6" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $quizName ?></h5>
                            <pre class="card-text preview"><?php echo $questions ?></pre>
                            <a href='startquizz.php?id=<?php echo $quizID ?>' class="btn btn-dark">Start
                                Quiz</a>
                            <a href='editquizz.php?id=<?php echo $quizID ?>' class="btn btn-dark"> Edit Quiz</a>
                            <button type="button" id="<?php echo $quizID ?>" class="btn btn-dark"
                                    data-toggle="modal" data-target="#deleteModal">
                                Delete Quiz
                            </button>
                        </div>
                    </div>
                <?php endwhile;
            } ?>
        </div>
    </div>


</div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog"
     aria-labelledby="deleteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModal">Are you sure you want to delete this quiz?</h5>
            </div>
            <div class="modal-footer">
                <a href="#" id="delete" class="btn btn-dark">Delete</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
include("../layout/footer.php")


?>
<script>
    // give the modal the right href= url to delete
    $("button").click(function () {
        $("#delete").attr('href', "deletequizz.php?id=" + this.id);
    });
    searchQuiz()

    function searchQuiz() {
        var card, cardbody, headline, input, filter;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        card = document.getElementById("row");
        cardbody = card.getElementsByClassName("card");
        for (var i = 0; i < cardbody.length; i++) {
            headline = cardbody[i].getElementsByTagName("h5")[0];
            if (headline) {
                if (headline.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    cardbody[i].style.display = "";
                } else {
                    cardbody[i].style.display = "none";
                }
            }
        }
    }
</script>
