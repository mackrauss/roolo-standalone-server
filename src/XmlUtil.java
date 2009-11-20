import java.io.PrintWriter;
import java.util.List;

import roolo.api.search.ISearchResult;
import roolo.elo.api.I18nType;
import roolo.elo.api.IELO;
import roolo.elo.api.IMetadataKey;
import roolo.elo.api.metadata.MetadataTokenization;
import roolo.elo.api.metadata.MetadataValueCount;
import roolo.elo.metadata.keys.StringMetadataKey;

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
	
	public static String generateElo(IELO elo){
		return elo.getXml();
	}
	
	public static String generateSearchResultList(List<ISearchResult> results ){
		String xml = "";
		
		String resultXml = null;
		for (ISearchResult searchResult : results) {
			resultXml = "";
			resultXml += "<uri>" + searchResult.getUri() + "</uri>";
			resultXml += "<version>" + searchResult.getVersion() + "</version>";
			resultXml += "<relevance>" + searchResult.getRelevance() + "</relevance>";
			xml += "<SearchResult>" + resultXml + "</SearchResult>";
		}
		
		return "<SearchResults>\n" + xml + "</SearchResults>";
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
