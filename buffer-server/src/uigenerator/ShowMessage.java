package uigenerator;

import org.jdom.Element;

import repo.Message;
import xml.JDomStringConversion;
import xml.XmlUtil;

public class ShowMessage implements IUiGenerator {

	public String generate(Message message) {
		String output = "";
		
		JDomStringConversion jdomConverter = new JDomStringConversion();
		System.out.println("XML: "+message.toXml());
		System.out.println("CONTENT: " + message.getContent());
		Element msgElem = jdomConverter.stringToXml(message.getContent());
		if (msgElem == null){
			return "ERROR (ShowMessage): Malformed XML. Could not find <message> in message content";
		}
		
		String alertMessage = msgElem.getText();
		output += "<script type='text/javascript'> alert('"+alertMessage+"'); </script>";
		
		return output;
	}

}
