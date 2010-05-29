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
		"Net Force",
		"One body problem",
		"Multiple body problem",
		"Collision",
		"Explosion",
		"Fast or instantaneous process",
		"1 dimensional",
		"2 dimensional",
		"Closed system",
		"Open system",
		"Conserved",
		"Not conserved",
		"Energy",
		"Momentum",
		"Impulse",
		"Force",
		"Displacement",
		"Velocity"
	);

	public static $forumulas = array(
	"Constant acceleration, changing velocity" => array(
		'id' => '1',
		'path' => '/Formulas/1.png'
	),
	"Constant acceleration, changing position" => array(
		'id' => '2',
		'path' => '/Formulas/2.png'
	),
	"Constant acceleration, time independent" => array(
		'id' => '3',
		'path' => '/Formulas/3.png'
	),
	"Newton's second law, sum of forces" => array(
		'id' => '4',
		'path' => '/Formulas/4.png'
	),
	"Frictional force" => array(
		'id' => '5',
		'path' => '/Formulas/5.png'
	),
	"Centripetal acceleration" => array(
		'id' => '6',
		'path' => '/Formulas/6.png'
	),
	"Torque" => array(
		'id' => '7',
		'path' => '/Formulas/7.png'
	),
	"Momentum, one-dimension" => array(
		'id' => '8',
		'path' => '/Formulas/8.png'
	),
	"Impulse" => array(
		'id' => '9',
		'path' => '/Formulas/9.png'
	),
	"Kinetic energy" => array(
		'id' => '10',
		'path' => '/Formulas/10.png'
	),
	"Gravitational potential energy, human scale" => array(
		'id' => '11',
		'path' => '/Formulas/11.png'
	),
	"Work" => array(
		'id' => '12',
		'path' => '/Formulas/12.png'
	),
	"Power" => array(
		'id' => '13',
		'path' => '/Formulas/13.png'
	),
	"Force spring exerts" => array(
		'id' => '14',
		'path' => '/Formulas/14.png'
	),
	"Elastic potential energy of Hooke's Law spring" => array(
		'id' => '15',
		'path' => '/Formulas/15.png'
	),
	"Period of a spring" => array(
		'id' => '16',
		'path' => '/Formulas/16.png'
	),
	"Period of a pendulum" => array(
		'id' => '17',
		'path' => '/Formulas/17.png'
	),
	"Relationship between period and frequency" => array(
		'id' => '18',
		'path' => '/Formulas/18.png'
	),
	"Universal law of gravitation" => array(
		'id' => '19',
		'path' => '/Formulas/19.png'
	),
	"Gravitational potential energy, planetary scale" => array(
		'id' => '20',
		'path' => '/Formulas/20.png'
	)
	);
	
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
	 * Relationship between a Long Question and its ConcetpQuestions for Step 4 
	 */
	public static $longQuestionConceptQuestions = array(
		"1" => array(
			"2",
			"3",
			"4",
			"5"
		), 
		"2" => array(
			"1",
			"6",
			"7",
			"8"
		)
	);
	


}
?>