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

public class UpdateELO extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
	static final long serialVersionUID = 1L;
	private RepositoryJcrImpl repositoryJcrImpl = new RepositoryJcrImpl();

	public static final String P_ELO_XML = "eloXML";
	
	public UpdateELO() {
		super();
	}   	
	
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		PrintWriter writer = response.getWriter();
		response.setContentType("text/xml; charset=UTF-8");
		
		String p_eloXml = request.getParameter(UpdateELO.P_ELO_XML);
		if (p_eloXml == null){
			XmlUtil.generateError("Must provide parameter called: " + UpdateELO.P_ELO_XML, writer);
			return;
		}
		
		try{
			IMetadataTypeManager<IMetadataKey> typeManager = new MetadataTypeManager<IMetadataKey>();
			
			IELOFactory<IMetadataKey> eloFactory = new JDomBasicELOFactory<IMetadataKey>(
																typeManager, 
																typeManager.getMetadataKey("uri"), 
																null);
			IELO<IMetadataKey> elo = eloFactory.createELOFromXml(p_eloXml);
			
			repositoryJcrImpl.updateELO(elo);
		}catch(Exception e){
			XmlUtil.generateError(e, writer);
			return;
		}
		
		XmlUtil.generateSuccess("Successfully updated ELO", writer);
	}  	
	
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doGet(request, response);
	}
}