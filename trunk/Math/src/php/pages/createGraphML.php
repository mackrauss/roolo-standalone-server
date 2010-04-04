<?php

require_once '../graphML/GraphML.php';

$graphML = new GraphML();
$groups = array('functions', 'graphing', 'trigonometry', 'algebra');

$graphML->setGroups($groups);
$graphML->createGroupGraphMLs();

echo "The groups were created";

?>