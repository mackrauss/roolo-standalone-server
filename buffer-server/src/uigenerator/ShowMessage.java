package uigenerator;

import org.jdom.Element;

import repo.Message;
import sun.io.MalformedInputException;
import xml.JDomStringConversion;

public class ShowMessage implements IUiGenerator {

	public Message generate(Message message) throws MalformedInputException {
		String output = "";
		
		JDomStringConversion jdomConverter = new JDomStringConversion();
		Element msgElem = jdomConverter.stringToXml(message.getContent());
		if (msgElem == null || !msgElem.getName().equals("message")){
			throw new MalformedInputException("Message with type "+message.getType()+" must have a <message>");
		}
		
		String alertMessage = msgElem.getText();
		message.setContent(alertMessage);
		
		return message;
	}

}
