package api;

import java.io.IOException;
import java.io.PrintWriter;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.jdom.Element;

import repo.Message;
import repo.MessageRepository;
import xml.JDomStringConversion;
import xml.XmlUtil;

/**
 * Servlet implementation class for Servlet: Post
 *
 */
 public class Post extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
   static final long serialVersionUID = 1L;
   
    /* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#HttpServlet()
	 */
	public Post() {
		super();
	}   	
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		PrintWriter writer = response.getWriter();
		response.setContentType("text/xml; charset=UTF-8");
		
		String 	msg = null,
				from = null,
				to = null,
				type = null,
				content = null;
		/**
		 * Grab and parse all the parameters:
		 * xml
		 */
		msg = request.getParameter("message");
		if (msg == null){
			XmlUtil.generateError("Must provide parameter called: message", writer);
			return;
		}
		
		JDomStringConversion jdomConverter = new JDomStringConversion();
		Element msgElem = jdomConverter.stringToXml(msg);
		if (msgElem == null){
			XmlUtil.generateError("Malformed XML. Cannot complete the parse.", writer);
			return;
		}

		
		Element fromElem = msgElem.getChild("from");
		if (fromElem == null){
			XmlUtil.generateError("Malformed XML. No 'from' tag exists under 'message'", writer);
			return;
		}
		from = fromElem.getText();
		
		Element toElem = msgElem.getChild("to");
		if (toElem == null){
			XmlUtil.generateError("Malformed XML. No 'to' tag exists under 'message'", writer);
			return;
		}
		to = toElem.getText();
		
		Element typeElem = msgElem.getChild("type");
		if (typeElem == null){
			XmlUtil.generateError("Malformed XML. No 'type' tag exists under 'message'", writer);
			return;
		}
		type = typeElem.getText();
		
		Element contentElem = msgElem.getChild("content");
		if (contentElem == null){
			XmlUtil.generateError("Malformed XML. No 'content' tag exists under 'message'", writer);
			return;
		}
//		content = contentElem.toString();
		System.out.println(jdomConverter.xmlToString(contentElem));
		
		Message msgObj = new Message(from, to, type, content);
		MessageRepository repo = MessageRepository.getInstance();
		repo.addMessage(msgObj);
		
		XmlUtil.generateSuccess("The message was successfully posted", writer);
	}  	
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doGet(request, response);
	}   	  	    
}