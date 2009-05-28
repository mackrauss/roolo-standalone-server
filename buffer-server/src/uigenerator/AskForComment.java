package uigenerator;

import org.jdom.Element;

import repo.Message;
import sun.io.MalformedInputException;
import xml.JDomStringConversion;

public class AskForComment implements IUiGenerator {

	public Message generate(Message message) throws MalformedInputException {
		String output = "";
		
		JDomStringConversion jdomConverter = new JDomStringConversion();
		Element msgElem = jdomConverter.stringToXml(message.getContent());
		if (msgElem == null || !msgElem.getName().equals("message")){
			throw new MalformedInputException("Message with type "+message.getType()+" must have a <message>");
		}
		
		String msgString = msgElem.getText();
		String submitBtnOnclick = "clearInteractionPane(); Post('userCommented','<comment>'+document.getElementById('commentBox').value+'</comment>')";
		
		output += 
			msgString + "<br/>" +
			"<textarea id='commentBox' name='commentBox' cols='50' rows='5'></textarea>"+
			"<input type='button'   id='submitBtn'  name='submitBtn' value='Comment' onclick=\""+submitBtnOnclick+"\" />"
			; 
		
		message.setContent(output);
		return message;
	}

}
