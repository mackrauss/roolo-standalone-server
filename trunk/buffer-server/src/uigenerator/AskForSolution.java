package uigenerator;

import org.jdom.Element;

import repo.Message;
import sun.io.MalformedInputException;
import xml.JDomStringConversion;

public class AskForSolution implements IUiGenerator {

	public Message generate(Message message) throws MalformedInputException {
		String output = "";
		
		output += 		
			"<form method='post' enctype='multipart/form-data' id='uploadform'>" +
			"	<input type='file' name='solution' size='30' class='fileinput'/><a href='#' class='uploadfile' onclick='uploadFile()'>Upload File</a>" +
			"</form>" +
			"<iframe src='' id='fileframe' name='fileframe' onLoad='sendImage()' style='visibility:hidden'></iframe>";
       
		message.setContent(output);
		return message;
	}
}
