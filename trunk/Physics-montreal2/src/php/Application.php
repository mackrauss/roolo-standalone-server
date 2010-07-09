<?php
class Application {
	public static $groups = array(
		"physics111", 
		"physics211", 
		"physics311", 
		"physics411", 
		"physics511", 
		"physics611"
	);
	
	public static $superGroups = array(
		"physicsGroup111", 
		"physicsGroup211",
		"physicsGroup311", 
		"physicsGroup411"
	);
	
	public static $superGroupSpecialties = array(	"superGroup1" => "First",
													"superGroup2" => "Second",
													"superGroup3" => "Third");
	public static $numProblems = 12;
	public static $numPrinciples = 3;
	public static $numProblemsForPhaseB = 3;
	public static $numChoicesPerProblem = 4;
	public static $problemChoices = array("a", "b", "c", "d", "e", "f", "g", "h");
	public static $problemCategories = array(
		"Newton First Law",
		"Newton Second Law",
		"Newton Third Law",
		"Net Force",
		"Collision",
		"1 Dimensional",
		"2 Dimensional",
		"Conservation of Energy",
		"Conservation of Momentum",
		"Work",
		"Linear Acceleration",
		"Centripetal Acceleration",
		"Elastic Potential Energy",
		"Gravitational Potential Energy",
		"Kinetic Energy",
		"Friction",
		"Force",
		"Displacement",
		"Velocity",
		"Center of Mass");
	

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
	
	
	public static $correctAnswers = array (
		"1" => "a",
		"2" => "c",
		"3" => "c",
		"4" => "d"
	);
	
	/*
	 * Relationship between students and the set of questions they have to answer. The numbers correspond to each question's unique id
	 */
	public static $studentQuestions = array(
		"physics111" => array("1", "2", "3", "4"),
		"physics211" => array("1", "2", "3", "4"),
		"physics311" => array("1", "2", "3", "4"),
		"physics411" => array("1", "2", "3", "4"),

		"physics511" => array("1", "5", "9", "13"),
		"physics611" => array("2", "6", "10", "14"),
		"physics711" => array("3", "7", "11", "15"),
		"physics811" => array("4", "8", "12", "16"),
	
		"physics911" => array("1", "5", "9", "13"),
		"physics111a" => array("2", "6", "10", "14"),
		"physics111b" => array("3", "7", "11", "15"),
		"physics111c" => array("4", "8", "12", "16"),
	
		"physics111d" => array("1", "5", "9", "13"),
		"physics111e" => array("2", "6", "10", "14"),
		"physics111f" => array("3", "7", "11", "15"),
		"physics111g" => array("4", "8", "12", "16")
	);

	
	/*
	 * Relationship between Groups and Questions assigned to them 
	 */
	public static $groupQuestions = array(
		"physicsGroup111" => array("1", "2", "3", "4"),
		"physicsGroup211" => array("1", "2", "3", "4"),
		"physicsGroup311" => array("1", "2", "3", "4"),
		"physicsGroup411" => array("1", "2", "3", "4"),
		"teacher" 		  => array("1", "2", "3", "4")
	);
	
	/*
	 * Relationship betweeen a Group and their Long Question for Step 4
	 */
	public static $groupLongQuestions = array(
		"physicsGroup111" => "19",
		"physicsGroup211" => "20",
		"physicsGroup311" => "18",
		"physicsGroup411" => "17"
	);
	
	/*
	 * Relationship between a Long Question and its ConcetpQuestions for Step 4 
	 */
	public static $longQuestionConceptQuestions = array(
		"17" => array(
			"9",
			"10",
			"11",
			"12"
		), 
		"18" => array(
			"13",
			"14",
			"15",
			"16"
		),
		"19" => array(
			"5",
			"6",
			"7",
			"8"
		),
		"20" => array(
			"1",
			"2",
			"3",
			"4"
		)
	);

	/*
	 * The below static questions arrays have been deffined for step seven. That is 
	 * a single page contains a long question and all 4 concept questions
	 * the currect result  
	 */
	public static $shortQuestions = array("1", "2", "3", "4");

	public static $longQuestion = "5";
	
	public static $authors = array("physicsGroup111", "physicsGroup211", "physicsGroup311", "physicsGroup411");
	
}
?>