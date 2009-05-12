

import java.io.IOException;
import java.io.PrintWriter;
import java.net.URI;
import java.net.URISyntaxException;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import roolo.elo.JDomBasicELOFactory;
import roolo.elo.MetadataTypeManagerForTesting;
import roolo.elo.RepositoryJcrImpl;
import roolo.elo.api.IELO;
import roolo.elo.api.IELOFactory;
import roolo.elo.api.IMetadataKey;
import roolo.elo.api.IMetadataTypeManager;

/**
 * Servlet implementation class for Servlet: UpdateELO
 *
 */
 public class UpdateELO extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
   static final long serialVersionUID = 1L;
   private RepositoryJcrImpl repositoryJcrImpl = new RepositoryJcrImpl();

    /* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#HttpServlet()
	 */
	public UpdateELO() {
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
		String eloXMLReceived = request.getParameter("eloXML");
		
		IMetadataTypeManager<IMetadataKey> typeManager = new MetadataTypeManagerForTesting<IMetadataKey>();
		IELOFactory<IMetadataKey> eloFactory = new JDomBasicELOFactory<IMetadataKey>(typeManager);
		IELO<IMetadataKey> elo = eloFactory.createELOFromXml(eloXMLReceived);
		
		repositoryJcrImpl.updateELO(elo);
		
		writer.write("Successfully updated ELO");
	}
}