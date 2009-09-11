import java.io.IOException;
import java.io.PrintWriter;
import java.net.URI;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import roolo.elo.ELOMetadataKeys;
import roolo.elo.JDomBasicELOFactory;
import roolo.elo.MetadataTypeManager;
import roolo.elo.RepositoryJcrImpl;
import roolo.elo.api.IELO;
import roolo.elo.api.IELOFactory;
import roolo.elo.api.IMetadataKey;
import roolo.elo.api.IMetadataTypeManager;
import roolo.elo.api.IMetadataValueContainer;
import roolo.elo.metadata.keys.BasicMetadataKey;

public class AddELO extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
	static final long serialVersionUID = 1L;
	private RepositoryJcrImpl repositoryJcrImpl = new RepositoryJcrImpl();
	
	public static final String P_ELO_XML = "eloXML";
	
	public AddELO() {
		super();
	}
	
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		PrintWriter writer = response.getWriter();
		response.setContentType("text/xml; charset=UTF-8");
		
		String p_eloXML = request.getParameter(AddELO.P_ELO_XML);
		if (p_eloXML == null){
			XmlUtil.generateError("Must provide parameter called: " + AddELO.P_ELO_XML, writer);
			return;
		}
		
		try{
			/*
			 * Replace all '&' chars with '||||' to make sure that even if the content XML is malformed, 
			 * it won't bother the SAX parser
			 */
//			p_eloXML = p_eloXML.replace("&", "||||");
			
			IMetadataTypeManager<BasicMetadataKey> typeManager = new MetadataTypeManager<BasicMetadataKey>();
			
			IELOFactory<BasicMetadataKey> eloFactory = new JDomBasicELOFactory<BasicMetadataKey>(typeManager);
			IELO<BasicMetadataKey> elo = eloFactory.createELOFromXml(p_eloXML);
			
			repositoryJcrImpl.addELO(elo);
		}catch(Exception e){
			XmlUtil.generateError(e, writer);
			return;
		}
		
		XmlUtil.generateSuccess("ELO added successfully", writer);
	}  	
	
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doGet(request, response);
	}
}