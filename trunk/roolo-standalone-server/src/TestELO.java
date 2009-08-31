

import java.io.IOException;
import java.io.PrintWriter;
import java.net.URI;
import java.net.URISyntaxException;
import java.sql.Date;
import java.util.List;
import java.util.Locale;
import java.util.logging.Level;
import java.util.logging.Logger;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.junit.After;
import org.junit.Assert;
import org.junit.Before;

import roolo.elo.BasicELO;
import roolo.elo.ELOMetadataKeys;
import roolo.elo.MetadataTypeManager;
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
import roolo.elo.metadata.keys.BasicMetadataKey;
import roolo.elo.metadata.keys.Contribute;
import roolo.elo.metadata.keys.LongMetadataKey;

/**
 * Servlet implementation class for Servlet: GetELO
 *
 */
 public class TestELO extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
   static final long serialVersionUID = 1L;
   private RepositoryJcrImpl repositoryJcrImpl = new RepositoryJcrImpl();
   private MetadataTypeManager<BasicMetadataKey> typeManager = new MetadataTypeManager<BasicMetadataKey>();
   
   
    /* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#HttpServlet()
	 */
	public TestELO() {
		super();
	}   	
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException{
		this.doPost(request, response);
	}  	
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		PrintWriter writer = response.getWriter();
		response.setContentType("text/xml; charset=UTF-8"); 
		
		try{
			/*************** brought over from TestELOAdd **************/
			String contentXML = "<body></body>";
			BasicContent eloContent = new BasicContent();
			eloContent.setXmlString(contentXML);
			
			//create elo
			BasicELO elo = this.createTestELO("", eloContent);
			
			//add elo
			IMetadata retMetadata = repositoryJcrImpl.addELO(elo);
			URI newEloUri = (URI)retMetadata.getMetadataValueContainer(typeManager.getMetadataKey("uri")).getValue();
			
			//retreive elo
			BasicELO retElo = (BasicELO) repositoryJcrImpl.retrieveELO(newEloUri);
			
			writer.write(retElo.getXml());
		}catch(URISyntaxException e){
			e.printStackTrace(writer);
		}
	}
	
	private void setContainersEssentialKeys(IMetadata metadata, IMetadataValueContainer container ){
		
		/*
		//Create container with essential keys and value
		container = metadata.getMetadataValueContainer(ELOMetadataKeys.TYPE.getKey());
		container.setValue("word");
		
		//Need to setValue the version key (because that's what creates it), but
		//don't give it an actual value because RepositoryJcrImpl manages versions itself
		container = metadata.getMetadataValueContainer(ELOMetadataKeys.VERSION.getKey());
		container.setValue("");
		
		container = metadata.getMetadataValueContainer(ELOMetadataKeys.TITLE.getKey());
		container.setValue("The TITLE of ELO");
		
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
		*/
		
		container = metadata.getMetadataValueContainer(typeManager.getMetadataKey("type"));
		container.setValue("word");
		
		container = metadata.getMetadataValueContainer(typeManager.getMetadataKey("title"));
		container.setValue("The TITLE of ELO");
		
		container = metadata.getMetadataValueContainer(typeManager.getMetadataKey("dateCreated"));
		container.setValue("");
		
		container = metadata.getMetadataValueContainer(typeManager.getMetadataKey("dateLastModified"));
		container.setValue("");
		
		container = metadata.getMetadataValueContainer(typeManager.getMetadataKey("version"));
		container.setValue("");
		
		container = metadata.getMetadataValueContainer(typeManager.getMetadataKey("author"));
		container.setValue("The AUTHOR of ELO");
		
		container = metadata.getMetadataValueContainer(typeManager.getMetadataKey("contribute"));
		Contribute contribObj = new Contribute("Contrib Card 1", new Date(2009, 8, 23).getTime());
		container.setValue(contribObj);
		
		container = metadata.getMetadataValueContainer(typeManager.getMetadataKey("keywords"));
		container.addValue("keyword1");
		container.addValue("keyword2");
		container.addValue("keyword3");
		
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
	
	private IMetadataKey createUriMetadataKey(){
//		String uriId = ELOMetadataKeys.URI.getId();
//		String uriXpath = ELOMetadataKeys.URI.getXpath();
//		I18nType uriType = ELOMetadataKeys.URI.getI18n();
//		MetadataValueCount uriCount = ELOMetadataKeys.URI.getCount();
//		IMetadataKey uriKey = new LongMetadataKey(uriId, uriXpath, uriType, uriCount , null);
		
		IMetadataKey uriKey = this.typeManager.getMetadataKey("uri");
		
		return uriKey;
	}
	
	private void emptyElosDirectory() {		
		repositoryJcrImpl.recreateRepository();
	}
	
	@Before
	public void setup() throws DeleteELOException, URISyntaxException{
		Logger logger = Logger.getLogger(MetadataTypeManager.class.getName());
		logger.setLevel(Level.OFF);
		
		this.emptyElosDirectory();
	}
	
	@After
	public void cleanup(){
		this.emptyElosDirectory();
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
		IMetadataKey uriKey = createUriMetadataKey();
		
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
		
		Assert.assertEquals("word", md.getMetadataValueContainer(typeManager.getMetadataKey("type")).getValue());
		Assert.assertEquals("The TITLE of ELO", md.getMetadataValueContainer(typeManager.getMetadataKey("title")).getValue());
		Assert.assertEquals("The AUTHOR of ELO", md.getMetadataValueContainer(typeManager.getMetadataKey("author")).getValue());
//		Contribute contribObj = new Contribute("Contrib Card 1", new Date(2009, 8, 23).getTime());
//		Assert.assertEquals(contribObj, md.getMetadataValueContainer(typeManager.getMetadataKey("contribute")).getValue());
		
		for (IMetadataKey curKey : typeManager.getCoreMetadataKeys()) {
			Assert.assertNotNull(md.getMetadataValueContainer(curKey).getValue());
		}
		
		/*
		Assert.assertEquals("The TITLE of ELO", md.getMetadataValueContainer(ELOMetadataKeys.TITLE.getKey()).getValue());
		Assert.assertEquals("ELO AUTHOR", md.getMetadataValueContainer(ELOMetadataKeys.AUTHOR.getKey()).getValue());
		Assert.assertEquals("This is the SUBJECT of ELO", md.getMetadataValueContainer(ELOMetadataKeys.SUBJECT.getKey()).getValue());
		Assert.assertEquals("GRADELEVEL 5", md.getMetadataValueContainer(ELOMetadataKeys.GRADELEVEL.getKey()).getValue());
		Assert.assertEquals("FAMILYTAG R", md.getMetadataValueContainer(ELOMetadataKeys.FAMILYTAG.getKey()).getValue());
		Assert.assertEquals("ISCURRENT Yes", md.getMetadataValueContainer(ELOMetadataKeys.ISCURRENT.getKey()).getValue());
		*/
	}	
}