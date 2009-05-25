import java.io.PrintWriter;
import java.util.List;

import roolo.elo.api.I18nType;
import roolo.elo.api.IELO;
import roolo.elo.api.IMetadataKey;
import roolo.elo.api.metadata.MetadataValueCount;
import roolo.elo.metadata.keys.StringMetadataKey;

public class XmlUtil {
	public static String generateEloList(List<IELO> elos){
		String elosXml = "";
		
		elosXml += "<EloList>";
		IMetadataKey relatedToMDK = new StringMetadataKey ("relatedTo", "/relatedto", I18nType.UNIVERSAL, MetadataValueCount.LIST, null);
		for (IELO curElo : elos) {
			elosXml += curElo.getXml();
//			String relatedto = curElo.getMetadata().getMetadataValueContainer(relatedToMDK).toString();
//			System.out.println("RELATED_TO_MDK: "+relatedto);
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
