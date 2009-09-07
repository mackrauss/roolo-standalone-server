import java.io.IOException;
import java.io.PrintWriter;
import java.net.URI;
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

/**
 * Servlet implementation class for Servlet: DeleteAll
 *
 */
 public class DeleteAll extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
   static final long serialVersionUID = 1L;
   private RepositoryJcrImpl repositoryJcrImpl = new RepositoryJcrImpl();
   
	public DeleteAll() {
		super();
	}   	
	
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		PrintWriter writer = response.getWriter();
		response.setContentType("text/xml; charset=UTF-8");
		
		String finalXml = "";
		
		try{
			List<String> eloUris = this.getAllEloUris();
			Iterator<String> eloIter = eloUris.iterator();
			while (eloIter.hasNext()){
				String uri = eloIter.next();
				this.repositoryJcrImpl.deleteELO(new URI(uri));
			}
		}catch(Exception e){
			XmlUtil.generateError(e, writer);
			return;
		}
		
		writer.write(finalXml);
	}  	
	
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doGet(request, response);
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