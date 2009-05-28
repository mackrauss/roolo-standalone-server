package uigenerator;

import org.jdom.Element;

import repo.Message;
import repo.MessageRepository;
import sun.io.MalformedInputException;
import xml.JDomStringConversion;

public class ShowQuestion implements IUiGenerator {

	public Message generate(Message message) throws MalformedInputException {
		String output = "";
		String contentServerAddress = "http://192.168.0.189";
		
		JDomStringConversion jdomConverter = new JDomStringConversion();
		Element imgElem = jdomConverter.stringToXml(message.getContent());
		if (imgElem == null || !imgElem.getName().equals("imageUrl")){
			throw new MalformedInputException("Message with type "+message.getType()+" must have a <imageUrl>");
		}
		
		String imgUrl = imgElem.getText();
		output += "<img border='0' src='"+contentServerAddress+imgUrl+"' alt='Question'>";
		
		message.setContent(output);
		
		if (message.getType().equals("showProblemToSolve")){
			MessageRepository repo = MessageRepository.getInstance();
			Message reminderMessage = new Message(message.getFrom(), message.getTo(), "askForSolution", "");
			repo.addMessage(reminderMessage);
		}
		
		return message;
	}
}
