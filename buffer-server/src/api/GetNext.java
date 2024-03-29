package api;

import java.io.IOException;
import java.io.PrintWriter;
import java.lang.reflect.Constructor;
import java.lang.reflect.InvocationTargetException;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import repo.Message;
import repo.MessageRepository;
import sun.io.MalformedInputException;
import uigenerator.IUiGenerator;
import xml.XmlUtil;

/**
 * Servlet implementation class for Servlet: GetNext
 *
 */
 public class GetNext extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
   static final long serialVersionUID = 1L;
   private Map<String, Class> typeToGeneratorMap;
   
    /* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#HttpServlet()
	 */
	public GetNext() {
		super();
	}   	
	
	@Override
	public void init() throws ServletException {
		// TODO Auto-generated method stub
		super.init();
		
		this.typeToGeneratorMap = new HashMap<String, Class>();
		this.typeToGeneratorMap.put("showMessage", uigenerator.ShowMessage.class);
		this.typeToGeneratorMap.put("listGroupMembers", uigenerator.ListGroupMembers.class);
		this.typeToGeneratorMap.put("showGroupCategory", uigenerator.ShowGroupCategory.class);
		this.typeToGeneratorMap.put("showProblemToTag", uigenerator.ShowQuestion.class);
		this.typeToGeneratorMap.put("showProblemToSolve", uigenerator.ShowQuestion.class);
		this.typeToGeneratorMap.put("askForConfirmation", uigenerator.AskForConfirmation.class);
		this.typeToGeneratorMap.put("askForComment", uigenerator.AskForComment.class);
		this.typeToGeneratorMap.put("askForSolution", uigenerator.AskForSolution.class);
	}
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		
		PrintWriter writer = response.getWriter();
		response.setContentType("text/html; charset=UTF-8");
		
		
		String to   = request.getParameter("to");
		if (to == null){
			XmlUtil.generateError("Must provide parameter called: to", writer);
			return;
		}
		
		String contentOnlyStr = request.getParameter("contentOnly");
		boolean contentOnly = false;
		if (contentOnlyStr != null && contentOnlyStr.equals("true")){
			contentOnly = true;
		}
		
		MessageRepository repo = MessageRepository.getInstance();
		Message messagesFound = repo.getNextMessage(to);
		String output = null;
		try{
			output = this.generateOutput(messagesFound, contentOnly);
		}catch(Exception e){
			XmlUtil.generateError(e, writer);
			return;
		}
		
		writer.write(output);
		
	}  	
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doGet(request, response);
	}   	
	
	private String generateOutput(Message curMessage, boolean contentOnly) throws NoSuchMethodException, InstantiationException, InvocationTargetException, IllegalAccessException, MalformedInputException{
		String output = "";
		
		//if curMessage is null, then return ""
		if (curMessage == null){
			return "";
		}
		
		String curMessageType = curMessage.getType();
		
		//if generator exists, utilize it		
		if (this.typeToGeneratorMap.get(curMessageType) != null){
			Class generatorClass = this.typeToGeneratorMap.get(curMessageType);
			Constructor generatorConstructor = generatorClass.getConstructor(new Class[] {});
			IUiGenerator generator = (IUiGenerator)generatorConstructor.newInstance(new Object[] {});
			curMessage = generator.generate(curMessage);
		}
		
		if (contentOnly){
			return curMessage.getContent();
		}else{
			return curMessage.toXml();
		}
		
	}
}