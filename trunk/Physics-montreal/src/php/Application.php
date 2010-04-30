<?php
class Application {
	public static $groups = array("group1", "group2", "group3", "group4", "group5", "group6");
	public static $superGroups = array("superGroup1", "superGroup2", "superGroup3");
	public static $superGroupSpecialties = array(	"superGroup1" => "First",
													"superGroup2" => "Second",
													"superGroup3" => "Third");
	public static $numProblems = 12;
	public static $numPrinciples = 3;
	public static $numProblemsForPhaseB = 3;
	public static $numChoicesPerProblem = 4;
	public static $problemChoices = array("a", "b", "c", "d", "e");
	public static $problemCategories = array(
		"net force",
		"One body problem",
		"Multiple body problem",
		"Collision",
		"Explosion",
		"Fast or instantaneous process",
		"1 dimensional",
		"2 dimensional",
		"closed system",
		"open system",
		"conserved",
		"not conserved",
		"energy",
		"momentum",
		"impulse",
		"force",
		"displacement",
		"velocity"
	);	
}
?>