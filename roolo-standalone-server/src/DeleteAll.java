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

import roolo.elo.EloUri;
import roolo.elo.RepositoryJcrImpl;

public class DeleteAll extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
	static final long serialVersionUID = 1L;
	private RepositoryJcrImpl repositoryJcrImpl;
   
	public DeleteAll() {
		super();
	}   	
	
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		PrintWriter writer = response.getWriter();
		response.setContentType("text/xml; charset=UTF-8");
		this.repositoryJcrImpl = RooloUtil.getRooloInstance(this.getServletContext());
		
		try{
			List<String> eloUris = EloUtil.getAllEloUris(repositoryJcrImpl);
			Iterator<String> eloIter = eloUris.iterator();
			while (eloIter.hasNext()){
				String uri = eloIter.next();
				this.repositoryJcrImpl.deleteELO(new EloUri(uri).convertToURI());
			}
		}catch(Exception e){
			XmlUtil.generateError(e, writer);
			return;
		}
		
		XmlUtil.generateSuccess("All ELOs were deleted successfully.", writer);
	}  	
	
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doGet(request, response);
	}
}