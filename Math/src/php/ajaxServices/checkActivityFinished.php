<?php

require_once '../agents/Grader.php';

$grader = new Grader();

try {
  $grader->process();
} catch (Exception $e) {
  header('ERROR', TRUE, 500);
  echo "<p style='background-color: #f99; padding: 10px; margin: 10px; margin-top: 30px'>".$e->getMessage()."</p>";
}
?>