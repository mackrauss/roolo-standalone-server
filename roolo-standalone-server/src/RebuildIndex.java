import java.io.IOException;
import java.io.PrintWriter;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import roolo.elo.RepositoryJcrImpl;

public class RebuildIndex extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
	static final long serialVersionUID = 1L;
	private RepositoryJcrImpl repositoryJcrImpl = new RepositoryJcrImpl();
	
	public RebuildIndex() {
		super();
	}
	
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		PrintWriter writer = response.getWriter();
		response.setContentType("text/xml; charset=UTF-8");
		
		
		try{
			repositoryJcrImpl.rebuildIndex();
		}catch(Exception e){
			XmlUtil.generateError(e, writer);
			return;
		}
		
		XmlUtil.generateSuccess("Index was rebuilt successfully", writer);
	}  	
	
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doGet(request, response);
	}
}