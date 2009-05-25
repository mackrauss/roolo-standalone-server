package uigenerator;

import repo.Message;
import sun.io.MalformedInputException;

public interface IUiGenerator {
	public Message generate(Message message) throws MalformedInputException;
}
