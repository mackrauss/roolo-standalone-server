package xml;

import java.io.PrintWriter;
import java.util.Iterator;
import java.util.List;

import repo.Message;

public class XmlUtil {
	public static void generateError(Exception e, PrintWriter writer){
		writer.write("<error>");
		e.printStackTrace(writer);
		writer.write("</error>");
	}
	
	public static void generateError(String message, PrintWriter writer){
		writer.write("<error>");
		//TODO: Add encoding to the message later on. EncodeURL.encode(message);
		writer.write(message);
		writer.write("</error>");
	}
	
	public static void generateSuccess(String message, PrintWriter writer){
		writer.write("<success>");
		writer.write(message);
		writer.write("</success>");
	}
	
	public static String generateMessageList(List<Message> messages){
		String out = "";
		
		Iterator<Message> messagesIter = messages.iterator();
		while(messagesIter.hasNext()){
			out += messagesIter.next().toXml();
		}
		
		return "<messages>"+out+"</messages>";
	}
}

