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
	public static $problemChoices = array("a", "b", "c", "d", "e", "f", "g", "h");
	public static $problemCategories = array(
		"Net Force",
		"Collision",
		"1 Dimentional",
		"2 Dimentional",
		"Conservation of Energy",
		"Conservation of Moemntum",
		"Work",
		"Linear Accelaration",
		"Centripetal acceleration",
		"Elastic Potential Energy",
		"Gravitational Potential Energy",
		"Kinetic Energy",
		"Friction",
		"Force",
		"Displacement",
		"Velocity",
		"Centre of Mass");
	

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
	 * Relationship between students and the set of questions they have to answer. The numbers correspond to each question's unique id
	 */
	public static $studentQuestions = array(
		"physics111" => array("1", "2", "3", "4", "5", "6"),
		"physics211" => array("5", "6", "7", "8", "9", "10"),
		"physics311" => array("9", "10", "11", "12", "13", "14"),
		"physics411" => array("13", "14", "15", "16"),

		"physics511" => array("1", "2", "3", "4", "5", "6"),
		"physics611" => array("5", "6", "7", "8", "9", "10"),
		"physics711" => array("9", "10", "11", "12", "13", "14"),
		"physics811" => array("13", "14", "15", "16"),
	
		"physics911" => array("1", "2", "3", "4", "5", "6"),
		"physics111a" => array("5", "6", "7", "8", "9", "10"),
		"physics111b" => array("9", "10", "11", "12", "13", "14"),
		"physics111c" => array("13", "14", "15", "16"),
	
		"physics111d" => array("1", "2", "3", "4", "5", "6"),
		"physics111e" => array("5", "6", "7", "8", "9", "10"),
		"physics111f" => array("9", "10", "11", "12", "13", "14"),
		"physics111g" => array("13", "14", "15", "16")
	);

	
	/*
	 * Relationship between Groups and Questions assigned to them 
	 */
	public static $groupQuestions = array(
		"physicsGroup111" => array("5", "6", "7", "8"),
		"physicsGroup211" => array("1", "2", "3", "4"),
		"physicsGroup311" => array("13", "14", "15", "16"),
		"physicsGroup411" => array("9", "10", "11", "12")
	);
	
	/*
	 * Relationship betweeen a Group and their Long Question for Step 4
	 */
	public static $groupLongQuestions = array(
		"physicsGroup111" => "17",
		"physicsGroup211" => "18",
		"physicsGroup311" => "19",
		"physicsGroup411" => "20"
	);
	
	/*
	 * Relationship between a Long Question and its ConcetpQuestions for Step 4 
	 */
	public static $longQuestionConceptQuestions = array(
		"17" => array(
			"2",
			"3",
			"4",
			"5"
		), 
		"18" => array(
			"1",
			"6",
			"7",
			"8"
		),
		"19" => array(
			"2",
			"3",
			"4",
			"5"
		),
		"20" => array(
			"2",
			"3",
			"4",
			"5"
		)
	);

}
?>