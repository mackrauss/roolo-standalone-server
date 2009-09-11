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

import roolo.elo.EloUri;
import roolo.elo.RepositoryJcrImpl;
import roolo.elo.api.IELO;

public class RetrieveAll extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
	static final long serialVersionUID = 1L;
	private RepositoryJcrImpl repositoryJcrImpl = new RepositoryJcrImpl();
   
	public RetrieveAll() {
		super();
	}
	
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doPost(request, response);
	}  	
	
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		PrintWriter writer = response.getWriter();
		response.setContentType("text/xml; charset=UTF-8");
		
		String finalXml = "";
		
		try{
			List<String> eloUris = EloUtil.getAllEloUris(this.repositoryJcrImpl);
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
			elos.add(this.repositoryJcrImpl.retrieveELO(new EloUri(eloUri).convertToURI()));
		}
		
		return elos;
	}
}