<?php

require_once '../graphML/GraphML.php';

$graphML = new GraphML();
$groups = array('Geometry', 'Exponential', 'Trigonometry', 'Algebra');

$graphML->setGroups($groups);
$graphML->createGroupGraphMLs();

echo "The groups were created";

?>