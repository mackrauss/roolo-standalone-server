import java.io.IOException;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.List;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import roolo.api.search.IQuery;
import roolo.api.search.ISearchResult;
import roolo.elo.RepositoryJcrImpl;
import roolo.elo.api.IELO;
import roolo.search.LuceneQuery;

public class Search extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
	static final long serialVersionUID = 1L;
	private RepositoryJcrImpl repositoryJcrImpl = new RepositoryJcrImpl();
	
	public static final String RESULT_TYPE_ELO 		= "elo";
	public static final String RESULT_TYPE_URI 		= "uri";
	
	public static final String SEARCH_SCOPE_ALL 	= "all";
	public static final String SEARCH_SCOPE_LATEST 	= "latest";
	
	public static final String INDEXED_DATA_ONLY_TRUE 	= "true";
	public static final String INDEXED_DATA_ONLY_FALSE 	= "false";
	
	public static final String P_QUERY 				= "query";
	public static final String P_RESULT_TYPE 		= "resultType";
	public static final String P_SEARCH_SCOPE 		= "searchScope";
	public static final String P_INDEXED_DATA_ONLY  = "indexedDataOnly";
	
	public Search() {
		super();
	}
	
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		PrintWriter writer = response.getWriter();
		response.setContentType("text/xml; charset=UTF-8");
		
		String p_queryStr = request.getParameter(Search.P_QUERY);
		if (p_queryStr == null){
			XmlUtil.generateError("Must provide parameter called: " + Search.P_QUERY, writer);
			return;
		}
		
		String p_resultType = request.getParameter(Search.P_RESULT_TYPE);
		if (p_resultType != null){
			if (!p_resultType.equals(Search.RESULT_TYPE_ELO) && !p_resultType.equals(Search.RESULT_TYPE_URI)){
				XmlUtil.generateError("The " + Search.P_RESULT_TYPE +" parameter may only be one of '" + Search.RESULT_TYPE_ELO + "' or '" + Search.RESULT_TYPE_URI + "'", writer);
				return;
			}
		}else{
			p_resultType = Search.RESULT_TYPE_URI;
		}
		
		String p_searchScope = request.getParameter(Search.P_SEARCH_SCOPE);
		if (p_searchScope != null){
			if (!p_searchScope.equals(Search.SEARCH_SCOPE_ALL) && !p_searchScope.equals(Search.SEARCH_SCOPE_LATEST)){
				XmlUtil.generateError("The " + Search.P_SEARCH_SCOPE + " parameter may only be one of '" + Search.SEARCH_SCOPE_ALL + "' or '" + Search.SEARCH_SCOPE_LATEST + "'", writer);
				return;
			}
		}else{
			p_searchScope = Search.SEARCH_SCOPE_ALL;
		}
		
		String indexedDataOnly = request.getParameter(Search.P_INDEXED_DATA_ONLY);
		boolean p_indexedDataOnly = false;
		if (indexedDataOnly != null){
			if (indexedDataOnly.equals(Search.INDEXED_DATA_ONLY_TRUE)){
				p_indexedDataOnly = true;
			}else if (indexedDataOnly.equals(Search.INDEXED_DATA_ONLY_FALSE)) {
				p_indexedDataOnly = false;
			}else{
				XmlUtil.generateError("The " + Search.P_INDEXED_DATA_ONLY + " parameter may only be one of " + Search.INDEXED_DATA_ONLY_TRUE + " or " + Search.INDEXED_DATA_ONLY_FALSE, writer);
				return;
			}
		}
		
		if (p_indexedDataOnly == true && p_resultType.equals(Search.RESULT_TYPE_ELO)){
			XmlUtil.generateError("You cannot set the resultType=elo and indexedDataOnly=true at the same time!", writer);
			return;
		}
		
		IQuery query = new LuceneQuery(p_queryStr, p_indexedDataOnly);
		
		String searchResultsXml = null;
		try{
			List<ISearchResult> searchResults = null;
			if (p_searchScope.equals(Search.SEARCH_SCOPE_ALL)){
				searchResults = repositoryJcrImpl.search(query);
			}else if (p_searchScope.equals(Search.SEARCH_SCOPE_LATEST)){
				searchResults = repositoryJcrImpl.searchLatest(query);
			}
			
			if (p_indexedDataOnly == true){
				List<IELO> searchResultElos = new ArrayList<IELO>();
				for (ISearchResult curSearchResult : searchResults) {
					searchResultElos.add(curSearchResult.getELO());
				}
				searchResultsXml = XmlUtil.generateEloList(searchResultElos);
			}else if (p_resultType.equals(Search.RESULT_TYPE_ELO)){
				List<IELO> searchResultElos = EloUtil.retrieveSearchResultElos(searchResults, this.repositoryJcrImpl);
				searchResultsXml = XmlUtil.generateEloList(searchResultElos);
			}else if (p_resultType.equals(Search.RESULT_TYPE_URI)) {
				searchResultsXml = XmlUtil.generateSearchResultList(searchResults);
			}
			
			writer.write(searchResultsXml);
		}catch(Exception e){
			XmlUtil.generateError(e, writer);
			return;
		}
	}
	
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException{
		this.doGet(request, response);
	}
}