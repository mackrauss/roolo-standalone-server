package uigenerator;

import org.jdom.Element;

import repo.Message;
import sun.io.MalformedInputException;
import xml.JDomStringConversion;

public class AskForConfirmation implements IUiGenerator {

	public Message generate(Message message) throws MalformedInputException {
		String output = "";
		
		JDomStringConversion jdomConverter = new JDomStringConversion();
		Element msgElem = jdomConverter.stringToXml(message.getContent());
		if (msgElem == null || !msgElem.getName().equals("message")){
			throw new MalformedInputException("Message with type "+message.getType()+" must have a <message>");
		}
		
		String msgString = msgElem.getText();
		String yesBtnOnclick = "clearInteractionPane(); Post('userConfirmed','<confirm>YES</confirm>');";
		String noBtnOnclick = "clearInteractionPane(); Post('userConfirmed','<confirm>NO</confirm>');";
		
		output += 
			msgString + "<br/>" +
			"<input id='yesBtn' name='yesBtn' type='button' value='Yes' onclick=\""+yesBtnOnclick+"\" />" +
			"<input id='noBtn' name='noBtn' type='button' value='No' onclick=\""+noBtnOnclick+"\" />"
			; 
		
		message.setContent(output);
		return message;
	}
}
