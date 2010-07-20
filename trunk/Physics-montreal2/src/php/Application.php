<?php
class Application {
	public static $groups = array(
		"student111", 
		"student211", 
		"student311", 
		"student411", 
		"student511", 
		"student611",
		"student711",
		"student811",
		"student911",
		"student1011",
		"student1111",
		"student1211",
		"student1311",
		"student1411",
		"student1511",
		"student1611",
		"student1711",
		"student1811",
		"student1911",
		"student2011",
		"student2111",
		"student2211",
		"student2311",
		"student2411",
		"student2511",
		"student2611",
		"student2711",
		"student2811",
		"student1911",
		"student3011"
	);
	
	public static $superGroups = array(
		"physicsgroup111", 
		"physicsgroup211",
		"physicsgroup311", 
		"physicsgroup411",
		"physicsgroup511",
		"physicsgroup611",
		"physicsgroup711",
		"physicsgroup811",
		"physicsgroup911",
		"physicsgroup1011",
		"physicsgroup1111",
		"physicsgroup1211",
		"physicsgroup1311",
		"physicsgroup1411",
		"physicsgroup1511"
	);
	
	public static $superGroupSpecialties = array(	"superGroup1" => "First",
													"superGroup2" => "Second",
													"superGroup3" => "Third");
	public static $numProblems = 12;
	public static $numPrinciples = 3;
	public static $numProblemsForPhaseB = 3;
	public static $numChoicesPerProblem = 4;
	public static $problemChoices = array("a", "b", "c", "d", "e", "f", "g");
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
	
	
	/*
	 * Question set 1
	 */
	public static $correctAnswers = array (
		"1" => "f",
		"2" => "e",
		"3" => "f"	
	);
	
//	/*
//	 * Question set 2
//	 */
//	public static $correctAnswers = array (
//		"1" => "a",
//		"2" => "b",
//		"3" => "c"	
//	);
	
	/*
	 * Relationship between students and the set of questions they have to answer. The numbers correspond to each question's unique id
	 */
	public static $studentQuestions = array(
		"student111" => array("1", "2", "3"),
		"student211" => array("1", "2", "3"),
		"student311" => array("1", "2", "3"),
		"student411" => array("1", "2", "3"),
		"student511" => array("1", "2", "3"),
		"student611" => array("1", "2", "3"),
		"student711" => array("1", "2", "3"),
		"student811" => array("1", "2", "3"),
		"student911" => array("1", "2", "3"),
		"student1011" => array("1", "2", "3"),
		"student1111" => array("1", "2", "3"),
		"student1211" => array("1", "2", "3"),
		"student1311" => array("1", "2", "3"),
		"student1411" => array("1", "2", "3"),
		"student1511" => array("1", "2", "3"),
		"student1611" => array("1", "2", "3"),
		"student1711" => array("1", "2", "3"),
		"student1811" => array("1", "2", "3"),
		"student1911" => array("1", "2", "3"),
		"student2011" => array("1", "2", "3"),
		"student2111" => array("1", "2", "3"),
		"student2211" => array("1", "2", "3"),
		"student2311" => array("1", "2", "3"),
		"student2411" => array("1", "2", "3"),
		"student2511" => array("1", "2", "3"),
		"student2611" => array("1", "2", "3"),
		"student2711" => array("1", "2", "3"),
		"student2811" => array("1", "2", "3"),
		"student2911" => array("1", "2", "3"),
		"student3011" => array("1", "2", "3")
	);

	
	/*
	 * Relationship between Groups and Questions assigned to them 
	 */
	public static $groupQuestions = array(
		"physicsgroup111" => array("1", "2", "3"),
		"physicsgroup211" => array("1", "2", "3"),
		"physicsgroup311" => array("1", "2", "3"),
		"physicsgroup411" => array("1", "2", "3"),
		"physicsgroup511" => array("1", "2", "3"),
		"physicsgroup611" => array("1", "2", "3"),
		"physicsgroup711" => array("1", "2", "3"),
		"physicsgroup811" => array("1", "2", "3"),
		"physicsgroup911" => array("1", "2", "3"),
		"physicsgroup1011" => array("1", "2", "3"),
		"physicsgroup1111" => array("1", "2", "3"),
		"physicsgroup1211" => array("1", "2", "3"),
		"physicsgroup1311" => array("1", "2", "3"),
		"physicsgroup1411" => array("1", "2", "3"),
		"physicsgroup1511" => array("1", "2", "3"),
		"teach11" 		  => array("1", "2", "3")
	);
	
	/*
	 * Relationship betweeen a Group and their Long Question for Step 4
	 */
	public static $groupLongQuestions = array(
		"physicsgroup111" => "19",
		"physicsgroup211" => "20",
		"physicsgroup311" => "18",
		"physicsgroup411" => "17"
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
	public static $shortQuestions = array("1", "2", "3");

	public static $longQuestion = "4";
	
	public static $authors = array(
		"physicsgroup111", 
		"physicsgroup211", 
		"physicsgroup311", 
		"physicsgroup411",
		"physicsgroup511",
		"physicsgroup611",
		"physicsgroup711",
		"physicsgroup811",
		"physicsgroup911",
		"physicsgroup1011",
		"physicsgroup1111",
		"physicsgroup1211",
		"physicsgroup1311",
		"physicsgroup1411",
		"physicsgroup1511"
	);
	
}
?>