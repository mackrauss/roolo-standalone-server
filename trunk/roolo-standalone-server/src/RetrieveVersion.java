import java.io.IOException;
import java.io.PrintWriter;
import java.net.URI;
import java.net.URISyntaxException;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import roolo.elo.RepositoryJcrImpl;
import roolo.elo.api.IELO;

public class RetrieveVersion extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
	static final long serialVersionUID = 1L;
	private RepositoryJcrImpl repositoryJcrImpl = new RepositoryJcrImpl();
	
	public static final String P_URI = "uri";
	public static final String P_VERSION = "version";

	public RetrieveVersion() {
		super();
	}   	
	
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		PrintWriter writer = response.getWriter();
		response.setContentType("text/xml; charset=UTF-8");
		
		String p_uri = request.getParameter(RetrieveVersion.P_URI);
		String p_version = request.getParameter(RetrieveVersion.P_VERSION);
		
		if (p_uri == null){
			XmlUtil.generateError("Must provide parameter called: " + RetrieveVersion.P_URI, writer);
			return;
		}
		
		if (p_version == null){
			XmlUtil.generateError("Must provide parameter called: " + RetrieveVersion.P_VERSION, writer);
			return;
		}
		
		try{
			IELO retrievedELO = repositoryJcrImpl.retrieveVersion(new URI(p_uri), p_version);
			writer.write(retrievedELO.getXml());
		}catch(URISyntaxException e){
			XmlUtil.generateError(e, writer);
			return;
		}
		
		
	}  	
	
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doGet(request, response);
	}
}