

import java.io.IOException;
import java.io.PrintWriter;
import java.net.URI;
import java.net.URISyntaxException;
import java.util.List;
import java.util.Locale;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.junit.After;
import org.junit.Assert;
import org.junit.Before;

import roolo.api.search.IQuery;
import roolo.api.search.ISearchResult;
import roolo.elo.BasicELO;
import roolo.elo.ELOMetadataKeys;
import roolo.elo.RepositoryJcrImpl;
import roolo.elo.api.I18nType;
import roolo.elo.api.IContent;
import roolo.elo.api.IMetadata;
import roolo.elo.api.IMetadataKey;
import roolo.elo.api.IMetadataValueContainer;
import roolo.elo.api.exceptions.DeleteELOException;
import roolo.elo.api.metadata.MetadataTokenization;
import roolo.elo.api.metadata.MetadataValueCount;
import roolo.elo.content.BasicContent;
import roolo.elo.metadata.keys.LongMetadataKey;
import roolo.search.LuceneQuery;

/**
 * Servlet implementation class for Servlet: Search
 *
 */
 public class Search extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
   static final long serialVersionUID = 1L;
   private RepositoryJcrImpl repositoryJcrImpl = new RepositoryJcrImpl();
   
    /* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#HttpServlet()
	 */
	public Search() {
		super();
	}
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		doPost(request, response);
	}
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException{
		PrintWriter writer = response.getWriter();
		response.setContentType("text/xml; charset=UTF-8");
		
//		try{
//			createTestElos();
//		}catch(URISyntaxException e){
//			e.printStackTrace(writer);
//		}
		
		String queryStr = request.getParameter("query");
		if (queryStr == null){
			XmlUtil.generateError("Must provide parameter called: query", writer);
			return;
		}
		IQuery query = new LuceneQuery(queryStr);
		
		String searchResultsXml = null;
		try{
			//TITLE:ELO
			List<ISearchResult> elosFound = repositoryJcrImpl.search(query);
			
			searchResultsXml = XmlUtil.generateSearchResultList(elosFound);
			writer.write(searchResultsXml);
		}catch(Exception e){
			XmlUtil.generateError(e, writer);
			return;
		}
		
//		writer.write(elosXml);
	}
	
	private void createTestElos() throws URISyntaxException{
		String eloURIStr = "StrContentLanguageIndependent";
		String contentXML = "xmlxmlxml";
		BasicContent eloContent = new BasicContent();
		eloContent.setXmlString(contentXML);
		
		//create elo
		BasicELO elo = this.createTestELO(eloURIStr, eloContent);
		
		//add elo
		repositoryJcrImpl.addELO(elo);
		
		//retreive elo
		BasicELO retElo = (BasicELO) repositoryJcrImpl.retrieveELO(new URI(eloURIStr));
	}
	
	/******************************************* HELPER METHODS ********************************************/
	private void setContainersEssentialKeys(IMetadata metadata, IMetadataValueContainer container ){
		//Create container with essential keys and value
		container = metadata.getMetadataValueContainer(ELOMetadataKeys.TYPE.getKey());
		container.setValue("Universal Type");
		
		container = metadata.getMetadataValueContainer(ELOMetadataKeys.VERSION.getKey());
		container.setValue("First VERSION");
		
		container = metadata.getMetadataValueContainer(ELOMetadataKeys.TITLE.getKey());
		container.setValue("ELO");
		
		container = metadata.getMetadataValueContainer(ELOMetadataKeys.AUTHOR.getKey());
		container.setValue("ELO AUTHOR");
		
		container = metadata.getMetadataValueContainer(ELOMetadataKeys.SUBJECT.getKey());
		container.setValue("This is the SUBJECT of ELO");
		
		container = metadata.getMetadataValueContainer(ELOMetadataKeys.GRADELEVEL.getKey());
		container.setValue("GRADELEVEL 5");
		
		container = metadata.getMetadataValueContainer(ELOMetadataKeys.FAMILYTAG.getKey());
		container.setValue("FAMILYTAG R");
		
		container = metadata.getMetadataValueContainer(ELOMetadataKeys.ISCURRENT.getKey());
		container.setValue("ISCURRENT Yes");
	}
	
	private BasicContent createBasicContent(Locale language, String xml){
		BasicContent content = new BasicContent();
		content.setXmlString(xml);
		content.setLanguage(language);
		
		return content;
	}
	/*
	 * This method creates an BasicContent instance with a list of languages
	 */
	private BasicContent createBasicContent(List<Locale> languages, String xml){
		BasicContent content = new BasicContent();
		content.setXmlString(xml);
		content.setLanguages(languages);
		
		return content;
	}
	
	private IMetadataKey createLongMetadataKey(){
		// change this, so that it gets the keys from the type manager
		// 
		String uriId = ELOMetadataKeys.URI.getId();
		String uriXpath = ELOMetadataKeys.URI.getXpath();
		I18nType uriType = ELOMetadataKeys.URI.getI18n();
		MetadataValueCount uriCount = ELOMetadataKeys.URI.getCount();
		
		IMetadataKey uriKey = new LongMetadataKey(uriId, uriXpath, uriType, uriCount , MetadataTokenization.UNTOKENIZED, null);
		
		return uriKey;
	}
	
	private void emptyElosDirectory() {		
//		repositoryJcrImpl.recreateRepository();
	}
	
	@Before
	public void setup() throws DeleteELOException, URISyntaxException{
//		this.emptyElosDirectory();
	}
	
	@After
	public void cleanup(){
//		this.emptyElosDirectory();
	}
	
	/**
	 * 
	 * @param uri
	 * @param content
	 * @return
	 * @throws URISyntaxException
	 */
	private BasicELO createTestELO(String uri, IContent content) throws URISyntaxException{
		//create ELO
		BasicELO elo = new BasicELO();
		
		//get ELO metadata
		IMetadata metadata = elo.getMetadata();
		
		//create ELO URI
		URI eloURI = new URI(uri);
		IMetadataKey uriKey = createLongMetadataKey();
		
		//get ELO's URI key container
		IMetadataValueContainer container = metadata.getMetadataValueContainer(uriKey);

		//prepare container by setting essential keys
		setContainersEssentialKeys(metadata, container);

		//set ELO's URI key
		elo.setUriKey(uriKey);
		//set ELO's URI key value
		container.setValue(eloURI);
		//set ELO Content
		elo.setContent(content);
		
		return elo;
	}
	
	private BasicELO createStringTestELO(String uriStr, String contentXMLStr) throws URISyntaxException{ 
		BasicContent eloContent = new BasicContent();
		eloContent.setXmlString(contentXMLStr);
		
		//create elo
		BasicELO elo = this.createTestELO(uriStr, eloContent);
		
		return elo;
	}
	
	private void checkEssentialMetadataKeysAndValues(BasicELO elo){
		IMetadata md = elo.getMetadata();
		Assert.assertEquals("Universal Type", md.getMetadataValueContainer(ELOMetadataKeys.TYPE.getKey()).getValue());
		Assert.assertEquals("First VERSION", md.getMetadataValueContainer(ELOMetadataKeys.VERSION.getKey()).getValue());
		Assert.assertEquals("The TITLE of ELO", md.getMetadataValueContainer(ELOMetadataKeys.TITLE.getKey()).getValue());
		Assert.assertEquals("ELO AUTHOR", md.getMetadataValueContainer(ELOMetadataKeys.AUTHOR.getKey()).getValue());
		Assert.assertEquals("This is the SUBJECT of ELO", md.getMetadataValueContainer(ELOMetadataKeys.SUBJECT.getKey()).getValue());
		Assert.assertEquals("GRADELEVEL 5", md.getMetadataValueContainer(ELOMetadataKeys.GRADELEVEL.getKey()).getValue());
		Assert.assertEquals("FAMILYTAG R", md.getMetadataValueContainer(ELOMetadataKeys.FAMILYTAG.getKey()).getValue());
		Assert.assertEquals("ISCURRENT Yes", md.getMetadataValueContainer(ELOMetadataKeys.ISCURRENT.getKey()).getValue());
	}
	
	/******************************************* TEST METHODS ********************************************/
	
}