import java.io.IOException;
import java.io.PrintWriter;
import java.util.List;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import roolo.api.search.IQuery;
import roolo.api.search.ISearchResult;
import roolo.elo.RepositoryJcrImpl;
import roolo.elo.api.IELO;
import roolo.search.LuceneQuery;

/**
 * Servlet implementation class for Servlet: SearchLatest
 *
 */
 public class SearchLatest extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
   static final long serialVersionUID = 1L;

   private RepositoryJcrImpl repositoryJcrImpl = new RepositoryJcrImpl();
   
   public static final String RESULT_TYPE_ELO = "elo";
   public static final String RESULT_TYPE_URI = "uri";
   
    /* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#HttpServlet()
	 */
	public SearchLatest() {
		super();
	}   	
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		doPost(request, response);
	}  	
	
	/* (non-Java-doc)
	 * @see javax.servlet.http.HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		PrintWriter writer = response.getWriter();
		response.setContentType("text/xml; charset=UTF-8");
		
		String p_queryStr = request.getParameter("query");
		if (p_queryStr == null){
			XmlUtil.generateError("Must provide parameter called: query", writer);
			return;
		}
		
		String p_resultType = request.getParameter("resultType");
		if (p_resultType != null){
			p_resultType = p_resultType.toLowerCase();
			if (!p_resultType.equals(Search.RESULT_TYPE_ELO) && !p_resultType.equals(Search.RESULT_TYPE_URI)){
				XmlUtil.generateError("The resultType parameter may only be '" + Search.RESULT_TYPE_ELO + "' or '" + Search.RESULT_TYPE_URI + "'", writer);
				return;
			}
		}else{
			p_resultType = Search.RESULT_TYPE_URI;
		}
		
		/*
		 * TODO: CHANGE THIS TO ONLY RETURN LATEST 
		 */
		IQuery query = new LuceneQuery(p_queryStr);
		
		String searchResultsXml = null;
		try{
			//TITLE:ELO
			List<ISearchResult> searchResultUris = repositoryJcrImpl.search(query);
			
			if (p_resultType.equals(Search.RESULT_TYPE_ELO)){
				List<IELO> searchResultElos = EloUtil.retrieveSearchResultElos(searchResultUris, this.repositoryJcrImpl);
				searchResultsXml = XmlUtil.generateEloList(searchResultElos);
			}else if (p_resultType.equals(Search.RESULT_TYPE_URI)) {
				searchResultsXml = XmlUtil.generateSearchResultList(searchResultUris);
			}
			
			writer.write(searchResultsXml);
		}catch(Exception e){
			XmlUtil.generateError(e, writer);
			return;
		}
	}   	  	    
}