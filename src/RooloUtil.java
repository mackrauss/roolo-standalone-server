import javax.servlet.ServletContext;

import roolo.elo.RepositoryJcrImpl;

public class RooloUtil {
	
	public static RepositoryJcrImpl getRooloInstance(ServletContext context){
		if (context.getAttribute("roolo") == null){
			context.setAttribute("roolo", new RepositoryJcrImpl());
		}
		
		return (RepositoryJcrImpl) context.getAttribute("roolo");
	}
}
