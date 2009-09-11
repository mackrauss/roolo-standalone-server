import java.io.IOException;
import java.io.PrintWriter;
import java.net.URI;
import java.net.URISyntaxException;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import roolo.elo.RepositoryJcrImpl;
import roolo.elo.api.IELO;

public class RetrieveFirst extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
	static final long serialVersionUID = 1L;
	private RepositoryJcrImpl repositoryJcrImpl = new RepositoryJcrImpl();
	
	public static final String P_URI = "uri";
	
	public RetrieveFirst() {
		super(); 
	}   	
	
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		PrintWriter writer = response.getWriter();
		response.setContentType("text/xml; charset=UTF-8");
		
		String p_uri = request.getParameter(RetrieveFirst.P_URI);
		if (p_uri == null){
			XmlUtil.generateError("Must provide parameter called: " + RetrieveFirst.P_URI, writer);
			return;
		}
		
		try{
			IELO retrievedELO = repositoryJcrImpl.retrieveFirst(new URI(p_uri));
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