<?php
class Application {
	public static $groups = array("group1", "group2", "group3", "group4");
	public static $superGroups = array("superGroup1", "superGroup2");
	public static $superGroupSpecialties = array(	"superGroup1" => "First Law",
													"superGroup2" => "Second Law");
	
	/*
	 * Relationship between Groups and Questions assigned to them 
	 */
	public static $groupQuestions = array(
		"group1" => array(
			"",
			""
		),
		"group2" => array(
			"",
			""
		)
	);
	
	/*
	 * Relationship betweeen a Group and their Long Question for Step 4
	 */
	public static $groupLongQuestions = array(
		"group1" => "1",
		"group2" => "2"
	);
	
	/*
	 * Relationship between a Question and it's ConcetpQuestions for Step 4 
	 */
	public static $questionConceptQuestions = array(
		"1" => array(
			"2",
			"3",
			"4",
			"5"
		)
	);

}
?>