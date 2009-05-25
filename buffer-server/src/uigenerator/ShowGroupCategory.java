package uigenerator;

import java.util.Iterator;
import java.util.List;

import org.jdom.Element;

import repo.Message;
import sun.io.MalformedInputException;
import xml.JDomStringConversion;

public class ShowGroupCategory implements IUiGenerator {

	public Message generate(Message message) throws MalformedInputException {
		String output = "";
		
		JDomStringConversion jdomConverter = new JDomStringConversion();
		Element tagElem = jdomConverter.stringToXml(message.getContent());
		if (tagElem == null || !tagElem.getName().equals("tag")){
			throw new MalformedInputException("Message with type "+message.getType()+" must have a <tag>");
		}
		output += "<b>"+tagElem.getText()+"</b>";
		
		message.setContent(output);
		return message;
	}

}
