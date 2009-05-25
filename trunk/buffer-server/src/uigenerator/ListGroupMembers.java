package uigenerator;

import java.util.Iterator;
import java.util.List;

import org.jdom.Element;

import repo.Message;
import sun.io.MalformedInputException;
import xml.JDomStringConversion;

public class ListGroupMembers implements IUiGenerator {

	public Message generate(Message message) throws MalformedInputException{
		String output = "";
		
		JDomStringConversion jdomConverter = new JDomStringConversion();
		Element groupElem = jdomConverter.stringToXml(message.getContent());
		if (groupElem == null || !groupElem.getName().equals("group")){
			throw new MalformedInputException("Message with type "+message.getType()+" must have a <group>");
		}
		
		List memberElems = groupElem.getChildren("member");
		Iterator<Element> membersIter = memberElems.iterator();
		while (membersIter.hasNext()){
			Element curMemberElem = membersIter.next();
			
			output += "<li>"+curMemberElem.getText()+"</li>";
		}
		output = "<ul>"+output+"</ul>";
		
		message.setContent(output);
		return message;
	}

}





