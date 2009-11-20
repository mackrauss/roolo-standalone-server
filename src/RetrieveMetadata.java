import java.io.IOException;
import java.io.PrintWriter;
import java.net.URI;
import java.net.URISyntaxException;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import roolo.elo.RepositoryJcrImpl;
import roolo.elo.api.IMetadata;

public class RetrieveMetadata extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
	static final long serialVersionUID = 1L;
	private RepositoryJcrImpl repositoryJcrImpl;

	public static final String P_URI = "uri";
	
	public RetrieveMetadata() {
		super();
	}   	
	
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		PrintWriter writer = response.getWriter();
		response.setContentType("text/xml; charset=UTF-8");
		this.repositoryJcrImpl = RooloUtil.getRooloInstance(this.getServletContext());
		
		String eloURIString = request.getParameter(RetrieveMetadata.P_URI);
		if (eloURIString == null){
			XmlUtil.generateError("Must provide parameter called: " + RetrieveMetadata.P_URI, writer);
			return;
		}
		
		try{
			IMetadata retrievedMetadata = repositoryJcrImpl.retrieveMetadata(new URI(eloURIString));
			writer.write(retrievedMetadata.getXml());
		}catch(URISyntaxException e){
			XmlUtil.generateError(e, writer);
			return;
		}
	}  	
	
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doGet(request, response);
	}
}