<?php

require_once '../domLib/simple_html_dom.php';


class GraphML {

	private $graphMLGroups;
	private $header;
	private $footer;
	
	public function __construct(){
		
		// Generating the header for the graphml files
		$this->header = "<graphml xmlns='http://graphml.graphdrawing.org/xmlns' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://graphml.graphdrawing.org/xmlns http://graphml.graphdrawing.org/xmlns/1.0/graphml.xsd'>\n\n";
		$this->header .=  "<key id='dn0' for='node' attr.name='type' attr.type='string' />\n";
		$this->header .=  "<key id='dn1' for='node' attr.name='group' attr.type='string' />\n";
		$this->header .=  "<key id='dn2' for='node' attr.name='imgLink' attr.type='string' />\n";
		$this->header .=  "<key id='de0' for='edge' attr.name='group' attr.type='string' />\n";
		$this->header .=  "<key id='de1' for='edge' attr.name='agree' attr.type='int'>\n";
		$this->header .=  "\t<default>0</default>\n";
		$this->header .=  "</key>\n";
		$this->header .=  "<key id='de2' for='edge' attr.name='disagree' attr.type='int'>\n";
		$this->header .=  "\t<default>0</default>\n";
		$this->header .=  "</key>\n\n";
		$this->header .=  "<graph id='G' edgedefault='undirected'>";
		
		// Generating the footer for the graphml files
		$this->footer = "\n</graph>\n\n</graphml>";
	}
	
	
	public function setGroups($groups) {
		$this->graphMLGroups = $groups;
	}
	
	public function getGroups(){
		return $this->graphMLGroups;
	}
	
	/**
	 * This method can be called to create all the graphML files for each
	 * individual group as well as the classroom's.
	 *
	 * By default if the file exists, it will NOT be deleted (i.e it will be kept as is)
	 */
	public function createGroupGraphMLs($forceDelete=false) {
		
		// iterate through each graphML group, create the XML node for it.
		// Aggregate all nodes to later on put in main screen graphML file.
		$mainScreen = '';
		foreach ($this->graphMLGroups as $key => $graphMLGroup){
			
			$key = $key+1;
			
			$filePath = dirname(__FILE__) . '/../../graphML/' . $key+1 . '.graphml';
			
			$curNode = $this->buildGraphMLNode('group', $graphMLGroup);
			$mainScreen .= $curNode;
			
			// Write the file out
			if (file_exists('/Users/jslottaresearch/Zend/workspaces/DefaultWorkspace/Math/src/php/graphML/' . $key . '.graphml')){
				unlink('/Users/jslottaresearch/Zend/workspaces/DefaultWorkspace/Math/src/php/graphML/' . $key . '.graphml');
			}
			$fileHandle = fopen('/Users/jslottaresearch/Zend/workspaces/DefaultWorkspace/Math/src/php/graphML/' . $key . '.graphml', 'a');
			$fileContent = $this->header . $curNode . $this->footer;
			fwrite($fileHandle, $fileContent);
			fclose($fileHandle);
		}
		
		if (file_exists('/Users/jslottaresearch/Zend/workspaces/DefaultWorkspace/Math/src/php/graphML/classroom.graphml')){
			// Write the main classroom graphml file
			unlink('/Users/jslottaresearch/Zend/workspaces/DefaultWorkspace/Math/src/php/graphML/classroom.graphml');
		}
		
		$fileHandle = fopen('/Users/jslottaresearch/Zend/workspaces/DefaultWorkspace/Math/src/php/graphML/classroom.graphml', 'a');
		$fileContent = $this->header . $mainScreen . $this->footer;
		fwrite($fileHandle, $fileContent);
		fclose($fileHandle);
		
	}
	
	public function getHeader () {
		return $this->header;
	}
	
	public function getFooter () {
		return $this->footer;
	}
	
	/**
	 * Build the XML that represents a graphML node
	 *
	 */
	public function buildGraphMLNode($nodeType, $nodeGroup, $nodeLink = ''){
		$node = "\n\t<node id='" . $nodeGroup . "'>\n";
		$node .= "\t\t<data key='dn0'>" . $nodeType . "</data>\n";
		$node .= "\t\t<data key='dn1'>" . $nodeGroup . "</data>\n";
		$node .= "\t\t<data key='dn2'>" . $nodeLink . "</data>\n";
		$node .= "\t</node>";
		
		return $node;
		
	}
	
	
	/**
	 * Build the XML that represents a graphML edge
	 *
	 */
	public function buildGraphMLEdge($source, $target){
		$edge = "\n\t<edge id='" . $source . "-" . $target . "' source='" . $source . "' target='" . $target . "'>\n";
		$edge .= "\t\t<data key='de0'>" . $target . "</data>\n";
		$edge .= "\t\t<data key='de1'>0</data>\n";
		$edge .= "\t\t<data key='de2'>0</data>\n";
		$edge .= "\t</edge>";
		
		return $edge;
	}
	
	/**
	 * get the graphNode element of the given file
	 *
	 * @param unknown_type $node
	 * @param unknown_type $file
	 */
	public function getGraphNode($file){
		
		// get all the content of the given file, add the node and write the content back to the file
		$path = '/Users/jslottaresearch/Zend/workspaces/DefaultWorkspace/Math/src/php/graphML/';
		
		$fileDom = file_get_dom($path . $file);
		$graphNode = $fileDom->find('graph');
		$graphNode = $graphNode[0];
		return $graphNode->innertext();
	}
	
	
	/**
	 * Replace the given file's content with the new content.
	 * NOTE: this method deletes the file and recreates it with the new content
	 *
	 * @param unknown_type $edge
	 * @param unknown_type $file
	 */
	public function updateFile($file, $content){
		// update the given file with the provided content
		$path = '/Users/jslottaresearch/Zend/workspaces/DefaultWorkspace/Math/src/php/graphML/';
		
		unlink($path . $file);

		$fileHandle = fopen($path . $file, 'a');
		fwrite($fileHandle, $content);
		fclose($fileHandle);
	}
	
}

?>