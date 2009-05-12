import java.io.PrintWriter;
import java.util.List;

import roolo.elo.api.IELO;

public class XmlUtil {
	public static String generateEloList(List<IELO> elos){
		String elosXml = "";
		
		elosXml += "<EloList>";
		
		for (IELO curElo : elos) {
			elosXml += curElo.getXml();
		}
		
		elosXml += "</EloList>";
		
		return elosXml;
	}
	
	public static void generateError(Exception e, PrintWriter writer){
		writer.write("<error>");
		e.printStackTrace(writer);
		writer.write("</error>");
	}
	
	public static void generateError(String message, PrintWriter writer){
		writer.write("<error>");
		writer.write(message);
		writer.write("</error>");
	}
	
	public static void generateSuccess(String message, PrintWriter writer){
		writer.write("<success>");
		writer.write(message);
		writer.write("</success>");
	}
}
