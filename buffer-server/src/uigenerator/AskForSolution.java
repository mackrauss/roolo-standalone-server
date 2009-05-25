package uigenerator;

import org.jdom.Element;

import repo.Message;
import sun.io.MalformedInputException;
import xml.JDomStringConversion;

public class AskForSolution implements IUiGenerator {

	public Message generate(Message message) throws MalformedInputException {
		String output = "";
		
		output += "";
		
		message.setContent(output);
		return message;
	}
}
