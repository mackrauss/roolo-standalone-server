<?php

require '../RooloClient.php';
require '../dataModels/Question.php';
require '../dataModels/QuestionCategory.php';

class Grader {

  private $rooloClient;

  function  __construct() {
    $this->rooloClient = new RooloClient();
  }

  public function process() {
    $studentReportCardContent = array();
    $questions = $this->fetchQuestions();
    $answers = $this->fetchAnswers();

    foreach($answers as $a) {
      $question_uri = $a->get_owneruri();
      $answer_uri = $a->get_uri();
      $question = null;
      // find the question matching the answer $a
      foreach($questions as $q) {
        if ($q->get_uri() == $question_uri) {
          $question = $q;
          break;
        }
      }

      if ($question === null)
        throw new Exception("Couldn't find question $question_uri for answer $answer_uri!");

      $solution = $question->get_masterSolution();
      
      if (!$solution || empty($solution))
        throw new Exception("Missing master solution for $question_uri!");

      $studentReportCardContent[$a->get_author()] = array(
        'question_uri' => $question_uri,
        'answer'   => $a->get_answer(),
        'solution' => $solution,
        'correct'  => $a->get_answer() == $solution
      );
    }


    print_r($studentReportCardContent);
  }


  private function fetchQuestions() {
    $query = 'type:Question';
    return $this->rooloClient->search($query, 'metadata', 'latest');
  }

  private function fetchAnswers() {
    $query = 'type:QuestionCategory';
    return $this->rooloClient->search($query, 'metadata', 'latest');
  }

}
?>
