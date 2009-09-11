import java.io.IOException;
import java.io.PrintWriter;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class UpdateMetadata extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
	static final long serialVersionUID = 1L;
   
	public UpdateMetadata() {
		super();
	}   	
	
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		// TODO waiting for a way to be able to objectify a metadata XML into an actual IMetadata object
		//      Currently that's not possible because the ELO factory doesn't expose the method for objectifying metadata
		PrintWriter writer = response.getWriter();
		response.setContentType("text/xml; charset=UTF-8");
		
		XmlUtil.generateError("The UpdateMetadata API has not been implemented yet", writer);
	}  	
	
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doGet(request, response);
	}   	  	    
}