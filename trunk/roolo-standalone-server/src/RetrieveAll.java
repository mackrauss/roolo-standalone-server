import java.io.IOException;
import java.io.PrintWriter;
import java.net.URI;
import java.net.URISyntaxException;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

import javax.jcr.LoginException;
import javax.jcr.Node;
import javax.jcr.RepositoryException;
import javax.jcr.Session;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import roolo.elo.RepositoryJcrImpl;
import roolo.elo.api.IELO;


/**
 * Servlet implementation class for Servlet: RetrieveAll
 *
 */
 public class RetrieveAll extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
   static final long serialVersionUID = 1L;
   private RepositoryJcrImpl repositoryJcrImpl = new RepositoryJcrImpl();
   
    /* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#HttpServlet()
	 */
	public RetrieveAll() {
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
		
		String finalXml = "";
		
		try{
			List<String> eloUris = this.getAllEloUris();
			List<IELO> elos = this.getElos(eloUris);
			finalXml += XmlUtil.generateEloList(elos);
		}catch(Exception e){
			XmlUtil.generateError(e, writer);
			return;
		}
		
		writer.write(finalXml);
	}
	
	/**
	 * Gets the ELOs present at the URIs provided by eloUris
	 * @param eloUris 
	 * @return 
	 * @throws URISyntaxException
	 */
	private List<IELO> getElos(List<String> eloUris) throws URISyntaxException{
		List<IELO> elos = new ArrayList<IELO>();
		
		for (String eloUri : eloUris) {
			elos.add(this.repositoryJcrImpl.retrieveELO(new URI(eloUri)));
		}
		
		return elos;
	}
	
	/**
	 * Gets the URIs of all the ELOs in the repository  
	 * @return URIs of all ELOs in repository
	 * @throws IOException
	 * @throws LoginException
	 * @throws RepositoryException
	 */
	private List<String> getAllEloUris() throws IOException, LoginException, RepositoryException{
		List<String> uris = new ArrayList<String>();
		Session session = this.repositoryJcrImpl.getNewSession();
		
		Node elosNode = session.getRootNode().getNode("elos");
		Iterator<Node> eloIter = elosNode.getNodes();
		while (eloIter.hasNext()){
			uris.add(eloIter.next().getName());
		}
		
		session.logout();
		
		return uris;
	}
}