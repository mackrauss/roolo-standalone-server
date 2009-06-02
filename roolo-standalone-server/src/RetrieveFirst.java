

import java.io.IOException;
import java.io.PrintWriter;
import java.net.URI;
import java.net.URISyntaxException;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import roolo.elo.RepositoryJcrImpl;
import roolo.elo.api.IELO;

/**
 * Servlet implementation class for Servlet: RetrieveFirst
 *
 */
 public class RetrieveFirst extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
   static final long serialVersionUID = 1L;
   private RepositoryJcrImpl repositoryJcrImpl = new RepositoryJcrImpl();
   
    /* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#HttpServlet()
	 */
	public RetrieveFirst() {
		super(); 
	}   	
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		PrintWriter writer = response.getWriter();
		response.setContentType("text/xml; charset=UTF-8");
		
		String eloURIString = request.getParameter("uri");
		if (eloURIString == null){
			XmlUtil.generateError("Must provide parameter called: uri", writer);
			return;
		}
		
		try{
			IELO retrievedELO = repositoryJcrImpl.retrieveFirst(new URI(eloURIString));
			writer.write(retrievedELO.getXml());
		}catch(URISyntaxException e){
			XmlUtil.generateError(e, writer);
			return;
		}
		
		
	}  	
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doGet(request, response);
	}   	  	    	  	    
}