<?php
require_once("include/initialize.php");

var_dump($_SESSION);
echo "<br><br>";
var_dump($_POST);

$score = 0;

$studentid = $_SESSION['USERID'];
$lessonid = $_POST['LessonID'];

foreach($_POST['choice'] as $exersiceid=>$value){

$sql = "SELECT * FROM `tblexercise` WHERE  `ExerciseID`='{$exersiceid}'";
$mydb->setQuery($sql);
$quiz = $mydb->loadSingleResult();

$answer = $quiz->Answer;
$lessonid = $quiz->LessonID;

if ($answer == $value) {
	# code... 
	$score= 1;
	// echo 'Correct';
}else{
	$score = 0;
	// echo 'Wrong';
}

$sql = "SELECT * From tblscore WHERE ExerciseID = '{$exersiceid}' AND StudentID='{$studentid}'";
$mydb->setQuery($sql);
$row = $mydb->executeQuery();
$maxrow = $mydb->num_rows($row);

if ($maxrow>0) { 
	$sql = "UPDATE tblscore SET Score='{$score}' WHERE ExerciseID = '{$exersiceid}' AND StudentID='{$studentid}'";  
	$mydb->setQuery($sql);
	$mydb->executeQuery();

}else{ 
	$sql = "INSERT INTO tblscore (`LessonID`,`ExerciseID`, `StudentID`, `Score`) VALUES ('{$lessonid}','{$exersiceid}','{$studentid}','{$score}')";
	$mydb->setQuery($sql);
	$mydb->executeQuery(); 
}
}
$sql = "Update tblscore Set Submitted = 1 WHERE LessonID='{$lessonid}' and StudentID='{$studentid}'";
$mydb->setQuery($sql);
$mydb->executeQuery();

$sql = "SELECT SUM(Score) as 'SCORE' FROM tblscore  WHERE LessonID='{$lessonid}' and StudentID='{$studentid}'";
$mydb->setQuery($sql);
$res = $mydb->loadSingleResult();
$score  = $res->SCORE;

message("Exercises already submitted.","sucess");
redirect("index.php?q=quizresult&id={$lessonid}&score={$score}");

?>