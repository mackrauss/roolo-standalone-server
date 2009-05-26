package api;

import java.io.IOException;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import repo.Message;
import repo.MessageRepository;
import uigenerator.ShowGroupCategory;

/**
 * Servlet implementation class for Servlet: EnterTestMessages
 *
 */
 public class EnterTestMessages extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
   static final long serialVersionUID = 1L;
   
    /* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#HttpServlet()
	 */
	public EnterTestMessages() {
		super();
	}   	
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		MessageRepository repo = MessageRepository.getInstance();
		String from = "agents";
		String to   = "rokham";
		
//		Message showMessage = new Message(from, to, "showMessage", "<message>hello world</message>");
//		repo.addMessage(showMessage);
		
		Message listGroupMembers = new Message(from, to, "listGroupMembers", "<group><member>m1</member><member>m2</member><member>m3</member></group>");
		repo.addMessage(listGroupMembers);
		
		Message showGroupCategory = new Message(from, to,"showGroupCategory", "<tag>Geometery</tag>");
		repo.addMessage(showGroupCategory);
		
		Message showProblemToTag = new Message(from, to, "showProblemToTag", "<imageUrl>http://docs.google.com/images/doclist/logo_docs.gif</imageUrl>");
		repo.addMessage(showProblemToTag);
		
//		Message askForConfirmation = new Message(from, to, "askForConfirmation", "<message>Do you agree?</message>");
//		repo.addMessage(askForConfirmation);
		
		Message askForSolution = new Message(from, to, "askForSolution", "");
		repo.addMessage(askForSolution);
		
		System.out.println(getServletContext().getRealPath("/"));
		
	}  	
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doGet(request, response);
	}   	  	    
}