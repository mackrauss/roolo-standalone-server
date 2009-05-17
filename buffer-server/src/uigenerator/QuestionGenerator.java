package uigenerator;

import repo.Message;

public class QuestionGenerator implements IUiGenerator {

	public String generate(Message message) {
		String output = "";
		
		//just imagine that the content is supposed to be the URL of the question's image
		String content = message.getContent();
		
		output += "<image src='"+content+"' height='20' width='30' alt='question to answer'/>";
		
		return output;
	}
}
