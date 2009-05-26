

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
 * Servlet implementation class for Servlet: ManualAddELO
 *
 */
 public class ManualAddELO extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
   static final long serialVersionUID = 1L;
   private RepositoryJcrImpl repositoryJcrImpl = new RepositoryJcrImpl();
   
    /* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#HttpServlet()
	 */
	public ManualAddELO() {
		super();
	}   	
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		PrintWriter writer = response.getWriter();

		for (int i=1;i<=51;i++){
			try{
				String num = Integer.toString(i);
				String eloXMLReceived = "<elo><metadata><uri>Question"+num+"</uri><type>question</type><version>1.0</version><title></title><author>MikeT</author><subject></subject><gradelevel></gradelevel><keywords></keywords><familytag></familytag><iscurrent>Yes</iscurrent><relatedto></relatedto><contributors></contributors><datecreated>Sat May 23 15:02:04 EDT 2009</datecreated></metadata><content languageIndependend='true' contentType='xml'><imageUrl>/question_images/q"+num+".jpg</imageUrl></content><resources></resources></elo>";
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
				
				repositoryJcrImpl.addELO(elo);
			}catch(Exception e){
				XmlUtil.generateError(e, writer);
				return;
			}
		}
		
		XmlUtil.generateSuccess("ELO added successfully", writer);
	}  	
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doGet(request, response);
	}   	  	    
}