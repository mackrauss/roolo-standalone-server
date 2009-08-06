

import java.io.IOException;
import java.io.PrintWriter;
import java.net.URI;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import roolo.elo.ELOMetadataKeys;
import roolo.elo.JDomBasicELOFactory;
import roolo.elo.RepositoryJcrImpl;
import roolo.elo.api.IELO;
import roolo.elo.api.IELOFactory;
import roolo.elo.api.IMetadataKey;
import roolo.elo.api.IMetadataTypeManager;
import roolo.elo.api.IMetadataValueContainer;

/**
 * Servlet implementation class for Servlet: AddELO
 *
 */
 public class AddELO extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
   static final long serialVersionUID = 1L;
   private RepositoryJcrImpl repositoryJcrImpl = new RepositoryJcrImpl();
   
    /* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#HttpServlet()
	 */
	public AddELO() {
		super();
	}
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doPost(request, response);
	}  	
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		PrintWriter writer = response.getWriter();
		response.setContentType("text/xml; charset=UTF-8");
		
		String eloXMLReceived = request.getParameter("eloXML");
		if (eloXMLReceived == null){
			XmlUtil.generateError("Must provide parameter called: eloXML", writer);
			return;
		}
		
//		String eloXMLReceived = "<elo><metadata><uri>StrContentLanguageIndependent2</uri><type>Universal Type</type><version>First VERSION</version><title>ELO</title><author>ELO AUTHOR</author><subject>This is the SUBJECT of ELO</subject><gradelevel>GRADELEVEL 5</gradelevel><familytag>FAMILYTAG R</familytag><iscurrent>ISCURRENT Yes</iscurrent><datecreated>Fri Apr 24 19:11:03 EDT 2009</datecreated></metadata><content languageIndependend='true' contentType='xml'><html><body>hello content</body></html></content><resources></resources></elo>";
		
		try{
			IMetadataTypeManager<IMetadataKey> typeManager = MetadataUtil.createTypeManager();
			
			IELOFactory<IMetadataKey> eloFactory = new JDomBasicELOFactory<IMetadataKey>(typeManager);
			IELO<IMetadataKey> elo = eloFactory.createELOFromXml(eloXMLReceived);
			
			//This statement CREATES the URI key in the ELO's Metadata
			IMetadataValueContainer uriKeyContainer = elo.getMetadata().getMetadataValueContainer(ELOMetadataKeys.URI.getKey());
			//this is the URI string set in the metadata, but it should be of type URI, so extract it and shove it into a URI object
			String uriString =  (String) uriKeyContainer.getValue();
			IMetadataKey uriKey = uriKeyContainer.getKey();
			uriKeyContainer.setValue(new URI(uriString));
			elo.setUriKey(uriKey);
			
			/**
			 * testing related_to mdk
			 */
//			IMetadataKey relatedToMDK = new StringMetadataKey ("relatedTo", "/relatedto", I18nType.UNIVERSAL, MetadataValueCount.LIST, null);
//			IMetadata metadata = elo.getMetadata();
//			IMetadataValueContainer relatedToMDC = metadata.getMetadataValueContainer(relatedToMDK);
//			relatedToMDC.addValue("myass1");
//			relatedToMDC.addValue("myass2");
//			relatedToMDC.addValue("myass3");
			/********* end test code ***********/
			
			repositoryJcrImpl.addELO(elo);
		}catch(Exception e){
			XmlUtil.generateError(e, writer);
			return;
		}
		
		XmlUtil.generateSuccess("ELO added successfully", writer);
	}
}