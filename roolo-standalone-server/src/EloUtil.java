import java.io.IOException;
import java.net.URI;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

import javax.jcr.LoginException;
import javax.jcr.Node;
import javax.jcr.RepositoryException;
import javax.jcr.Session;

import roolo.api.search.ISearchResult;
import roolo.elo.RepositoryJcrImpl;
import roolo.elo.api.IELO;

public class EloUtil {
	public static List<IELO> retrieveSearchResultElos(List<ISearchResult> searchResults, RepositoryJcrImpl repository){
		List<IELO> retrievedElos = new ArrayList<IELO>();
		for (ISearchResult curSearchResult : searchResults) {
			URI curEloUri = curSearchResult.getUri();
			String curEloVersion = curSearchResult.getVersion();
			
			retrievedElos.add(repository.retrieveVersion(curEloUri, curEloVersion));
		}
		return retrievedElos;
	}
	
	/**
	 * Gets the URIs of all the ELOs in the repository  
	 * @return URIs of all ELOs in repository
	 * @throws IOException
	 * @throws LoginException
	 * @throws RepositoryException
	 */
	public static List<String> getAllEloUris(RepositoryJcrImpl repositoryJcrImpl) throws IOException, LoginException, RepositoryException{
		List<String> uris = new ArrayList<String>();
		Session session = repositoryJcrImpl.getNewSession();
		
		Node elosNode = session.getRootNode().getNode("elos");
		Iterator<Node> eloIter = elosNode.getNodes();
		while (eloIter.hasNext()){
			uris.add(eloIter.next().getName());
		}
		
		session.logout();
		
		return uris;
	}
}
