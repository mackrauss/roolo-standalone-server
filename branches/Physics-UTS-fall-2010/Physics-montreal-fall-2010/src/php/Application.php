<?php
class Application {
//	public static $groups = array(
//		"physics111", 
//		"physics211", 
//		"physics311", 
//		"physics411", 
//		"physics511", 
//		"physics611"
//	);
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
		"Newton 1st Law",
		"Newton 2nd Law",
		"Newton 3rd Law",
		"One body problem",
		"Multiple body problem"
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
	
	
	public static $correctAnswers = array (
		"1" => "c",
		"2" => "a",
		"3" => "d",
		"4" => "b"		
	);
	
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
	
	public static $dropdownsItems = array(
		'Acceleration' => array('Acceleration', 'Zero', 'Non-zero constant', 'Non-constant'),
		'Net force' => array('Net force', 'Fnet=0', 'Fnet=non-zero constant', 'Fnet = non-constant'),
		'Forces' => array('Forces', 'Weight', 'Normal', 'Static friction', 'Kinetic friction', 'Tension', 'Other')
	);
	
	public static $letters = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","W","X","Y","Z");
	
	/*
	 * username => {
	 *     'password' => ... ,
	 *     'class' => ... 
	 * }
	 */
	public static $users = array(
		"MelissaA" => array('password' => 'Dawson', 'class' => '1'),
		"ElmehdiB" => array('password' => 'Dawson', 'class' => '1'),
		"SammyB" => array('password' => 'Dawson', 'class' => '1'),
		"EllaB" => array('password' => 'Dawson', 'class' => '1'),
		"LisaB" => array('password' => 'Dawson', 'class' => '1'),
		"MichaelD" => array('password' => 'Dawson', 'class' => '1'),
		"GabrielleD" => array('password' => 'Dawson', 'class' => '1'),
		"YasmineD" => array('password' => 'Dawson', 'class' => '1'),
		"SophyD" => array('password' => 'Dawson', 'class' => '1'),
		"AlexandreD" => array('password' => 'Dawson', 'class' => '1'),
		"GabrielleE" => array('password' => 'Dawson', 'class' => '1'),
		"ShannonE" => array('password' => 'Dawson', 'class' => '1'),
		"HannahF" => array('password' => 'Dawson', 'class' => '1'),
		"YuxiaG" => array('password' => 'Dawson', 'class' => '1'),
		"JonathanI" => array('password' => 'Dawson', 'class' => '1'),
		"XavierJ" => array('password' => 'Dawson', 'class' => '1'),
		"JoelleK" => array('password' => 'Dawson', 'class' => '1'),
		"AaronK" => array('password' => 'Dawson', 'class' => '1'),
		"JessicaM" => array('password' => 'Dawson', 'class' => '1'),
		"JenniferM" => array('password' => 'Dawson', 'class' => '1'),
		"MassimoM" => array('password' => 'Dawson', 'class' => '1'),
		"MikhailM" => array('password' => 'Dawson', 'class' => '1'),
		"Elyssa-MariaN" => array('password' => 'Dawson', 'class' => '1'),
		"MyleneP" => array('password' => 'Dawson', 'class' => '1'),
		"NathanielP" => array('password' => 'Dawson', 'class' => '1'),
		"CassandraP" => array('password' => 'Dawson', 'class' => '1'),
		"EkaterinaR" => array('password' => 'Dawson', 'class' => '1'),
		"JathushaS" => array('password' => 'Dawson', 'class' => '1'),
		"RainierS" => array('password' => 'Dawson', 'class' => '1'),
		"LaurenS" => array('password' => 'Dawson', 'class' => '1'),
		"NoamS" => array('password' => 'Dawson', 'class' => '1'),
		"BachirS" => array('password' => 'Dawson', 'class' => '1'),
		"VictorS" => array('password' => 'Dawson', 'class' => '1'),
		"VictorT" => array('password' => 'Dawson', 'class' => '1'),
		"YannickT" => array('password' => 'Dawson', 'class' => '1'),
		"CoryW" => array('password' => 'Dawson', 'class' => '1'),
	
		"Tester1" => array('password' => 'tester', 'class' => '1'),
		"Tester2" => array('password' => 'tester', 'class' => '1'),
	
		"test1" => array('password' => 'tester', 'class' => '1'),
		"test2" => array('password' => 'tester', 'class' => '1'),
		"test3" => array('password' => 'tester', 'class' => '1'),
		"test4" => array('password' => 'tester', 'class' => '1'),
		"test5" => array('password' => 'tester', 'class' => '1'),
		"test6" => array('password' => 'tester', 'class' => '1'),
		"test7" => array('password' => 'tester', 'class' => '1'),
		"test8" => array('password' => 'tester', 'class' => '1'),
		"test9" => array('password' => 'tester', 'class' => '1'),
		"test10" => array('password' => 'tester', 'class' => '1'),
		"test11" => array('password' => 'tester', 'class' => '1'),
		"test12" => array('password' => 'tester', 'class' => '1'),
		"test13" => array('password' => 'tester', 'class' => '1'),
		"test14" => array('password' => 'tester', 'class' => '1'),
		"test15" => array('password' => 'tester', 'class' => '1'),
		"test17" => array('password' => 'tester', 'class' => '1'),
		"test18" => array('password' => 'tester', 'class' => '1'),
		"test19" => array('password' => 'tester', 'class' => '1'),
		"test20" => array('password' => 'tester', 'class' => '1'),
		"test21" => array('password' => 'tester', 'class' => '1'),
		"test22" => array('password' => 'tester', 'class' => '1'),
		"test23" => array('password' => 'tester', 'class' => '1'),
		"test24" => array('password' => 'tester', 'class' => '1'),
		"test25" => array('password' => 'tester', 'class' => '1'),
		"test26" => array('password' => 'tester', 'class' => '1'),
	
		"CBTar" => array('password' => 'UTStudent', 'class' => '2'),
		"bonniebee" => array('password' => 'UTStudent', 'class' => '2'),
		"JasonHu" => array('password' => 'UTStudent', 'class' => '2'),
		"JimmyHe" => array('password' => 'UTStudent', 'class' => '2'),
		"KevenJi" => array('password' => 'UTStudent', 'class' => '2'),
		"VictorJi" => array('password' => 'UTStudent', 'class' => '2'),
		"LegendaryPokeMaster" => array('password' => 'UTStudent', 'class' => '2'),
		"Leithality" => array('password' => 'UTStudent', 'class' => '2'),
		"Avecnodove" => array('password' => 'UTStudent', 'class' => '2'),
		"Andyp" => array('password' => 'UTStudent', 'class' => '2'),
		"frankly" => array('password' => 'UTStudent', 'class' => '2'),
		"Ponyo" => array('password' => 'UTStudent', 'class' => '2'),
		"abcdjjj" => array('password' => 'UTStudent', 'class' => '2'),
		"praboud" => array('password' => 'UTStudent', 'class' => '2'),
		"alphatiger" => array('password' => 'UTStudent', 'class' => '2'),
		"Mr.Brooks" => array('password' => 'UTStudent', 'class' => '2'),
		"kamikazethundalemmings" => array('password' => 'UTStudent', 'class' => '2'),
		"EricZhan" => array('password' => 'UTStudent', 'class' => '2'),
		"Totoro" => array('password' => 'UTStudent', 'class' => '2'),
		"SarahZhang" => array('password' => 'UTStudent', 'class' => '2'),
	
		"Narora" => array('password' => 'UTStudent', 'class' => '1'),
		"anncheung" => array('password' => 'UTStudent', 'class' => '1'),
		"Salvador.Hutira" => array('password' => 'UTStudent', 'class' => '1'),
		"Grinsta" => array('password' => 'UTStudent', 'class' => '1'),
		"AlexanderMoonPaladin" => array('password' => 'UTStudent', 'class' => '1'),
		"Moonspear" => array('password' => 'UTStudent', 'class' => '1'),
		"Lincoln.Guo" => array('password' => 'UTStudent', 'class' => '1'),
		"ashketchum" => array('password' => 'UTStudent', 'class' => '1'),
		"vli" => array('password' => 'UTStudent', 'class' => '1'),
		"lolcats" => array('password' => 'UTStudent', 'class' => '1'),
		"laurpaur" => array('password' => 'UTStudent', 'class' => '1'),
		"qwong" => array('password' => 'UTStudent', 'class' => '1'),
		"victorlipaladin" => array('password' => 'UTStudent', 'class' => '1'),
		"Kwhittaker-lee" => array('password' => 'UTStudent', 'class' => '1'),
		"ghost1001" => array('password' => 'UTStudent', 'class' => '1'),
		"alenaz3" => array('password' => 'UTStudent', 'class' => '1')
	);
	
	public static $userGroups = array(
		"group1" => array('password' => 'UTStudent', 'class' => '1'),
		"group2" => array('password' => 'UTStudent', 'class' => '1'),
		"group3" => array('password' => 'UTStudent', 'class' => '1'),
		"group4" => array('password' => 'UTStudent', 'class' => '1'),
		"group5" => array('password' => 'UTStudent', 'class' => '1'),
		"group6" => array('password' => 'UTStudent', 'class' => '1'),
		"group7" => array('password' => 'UTStudent', 'class' => '1'),
		"group8" => array('password' => 'UTStudent', 'class' => '1'),
		"group9" => array('password' => 'UTStudent', 'class' => '1'),
		"group10" => array('password' => 'UTStudent', 'class' => '1'),
	
		"AP-B-Day-Group1" => array('password' => 'UTStudent', 'class' => '1'),
		"AP-B-Day-Group2" => array('password' => 'UTStudent', 'class' => '1'),
		"AP-B-Day-Group3" => array('password' => 'UTStudent', 'class' => '1'),
		"AP-B-Day-Group4" => array('password' => 'UTStudent', 'class' => '1'),
		"AP-B-Day-Group5" => array('password' => 'UTStudent', 'class' => '1'),
		"AP-B-Day-Group6" => array('password' => 'UTStudent', 'class' => '1'),
		"AP-B-Day-Group7" => array('password' => 'UTStudent', 'class' => '1'),
		"AP-B-Day-Group8" => array('password' => 'UTStudent', 'class' => '1'),
		"AP-B-Day-Group9" => array('password' => 'UTStudent', 'class' => '1'),
		"AP-B-Day-Group10" => array('password' => 'UTStudent', 'class' => '1'),
	
		"AP-A-Day-Group1" => array('password' => 'UTStudent', 'class' => '2'),
		"AP-A-Day-Group2" => array('password' => 'UTStudent', 'class' => '2'),
		"AP-A-Day-Group3" => array('password' => 'UTStudent', 'class' => '2'),
		"AP-A-Day-Group4" => array('password' => 'UTStudent', 'class' => '2'),
		"AP-A-Day-Group5" => array('password' => 'UTStudent', 'class' => '2'),
		"AP-A-Day-Group6" => array('password' => 'UTStudent', 'class' => '2'),
		"AP-A-Day-Group7" => array('password' => 'UTStudent', 'class' => '2'),
		"AP-A-Day-Group8" => array('password' => 'UTStudent', 'class' => '2'),
		"AP-A-Day-Group9" => array('password' => 'UTStudent', 'class' => '2'),
		"AP-A-Day-Group10" => array('password' => 'UTStudent', 'class' => '2'),
		
		"groupTest1" => array('password' => 'tester', 'class' => '1'),
		"groupTest2" => array('password' => 'tester', 'class' => '1'),
		"groupTest3" => array('password' => 'tester', 'class' => '1'),
		"groupTest4" => array('password' => 'tester', 'class' => '1'),
		"groupTest5" => array('password' => 'tester', 'class' => '1'),
		"groupTest6" => array('password' => 'tester', 'class' => '1'),
		"groupTest7" => array('password' => 'tester', 'class' => '1')
	);
	
	public static $admins = array(
		"teacher" => array('password' => 'UTSTeach')
	);
}
?>