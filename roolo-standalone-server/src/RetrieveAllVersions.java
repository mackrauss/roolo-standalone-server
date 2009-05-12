

import java.io.IOException;
import java.io.PrintWriter;
import java.net.URI;
import java.net.URISyntaxException;
import java.util.List;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import roolo.elo.BasicELO;
import roolo.elo.RepositoryJcrImpl;
import roolo.elo.api.IELO;

/**
 * Servlet implementation class for Servlet: RetrieveAllVersions
 *
 */
 public class RetrieveAllVersions extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
   static final long serialVersionUID = 1L;
   private RepositoryJcrImpl repositoryJcrImpl = new RepositoryJcrImpl();

    /* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#HttpServlet()
	 */
	public RetrieveAllVersions() {
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
			XmlUtil.generateError("Must provide a paramater: uri", writer);
		}
		
		String finalXml = "";
		
		try{
			List retrievedELOs = repositoryJcrImpl.retrieveAllVersions(new URI(eloURIString));
			String retrieveELOsXML = XmlUtil.generateEloList((List<IELO>)retrievedELOs);
			finalXml += retrieveELOsXML;
		}catch(URISyntaxException e){
			XmlUtil.generateError(e, writer);
		}
		
		writer.write(finalXml);
	}  	
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doGet(request, response);
	}
}