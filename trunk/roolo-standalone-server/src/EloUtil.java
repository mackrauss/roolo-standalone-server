import java.net.URI;
import java.util.ArrayList;
import java.util.List;

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
}
