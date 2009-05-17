package repo;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

/**
 * Singleton class that holds all the messages posted to the server
 * @author cfislotta
 *
 */
public class MessageRepository {
	
	private static MessageRepository repo;
	
	private List<Message> messages;
	
	private MessageRepository(){
		this.messages = new ArrayList<Message>();
	}
	
	public static MessageRepository getInstance(){
		if (repo == null){
			repo = new MessageRepository();
		}
		
		return repo;
	}
	
	/**
	 * Adds a new message to the repository.
	 * If a message with the same from & to fields exist, it will be replaced with msg
	 * @param msg
	 */
	public void addMessage(Message msg){
		int msgIndex = this.getMessageIndex(msg);
		
		if (msgIndex == -1){
			this.messages.add(msg);
		}else{
			this.messages.remove(msgIndex);
			this.messages.add(msg);
		}
	}
	
	/**
	 * Get a message for given from & to
	 * @param from
	 * @param to
	 * @return the message found, or null if no such message exists
	 */
	public Message getMessage(String from, String to){
		
		Message msg = new Message (from, to, "", "");
		
		synchronized (this) {
			int msgIndex = this.getMessageIndex(msg);
			
			if (msgIndex == -1){
				return null;
			}else{
				Message msgToReturn = messages.get(msgIndex).clone();
				this.messages.remove(msgIndex);
				return msgToReturn;
			}
		}
	}
	
	/**
	 * Return a list of messages that are sent to "to"
	 */
	public List getMessages(String to){
		List messagesToReturn = new ArrayList<Message>();
		List messagesToReturnIndices = new ArrayList<Integer>();
		
		int numMessages = this.messages.size();
		Message curMessage = null;
		synchronized (this) {
			/*
			 * Find all the right messages and store their clones in messagesToReturn
			 */
			for(int i=0;i<numMessages;i++){
				curMessage = this.messages.get(i);
				if (curMessage.getTo().equals(to)){
					messagesToReturn.add(curMessage.clone());
					messagesToReturnIndices.add(new Integer(i));
				}
			}
			
			/*
			 * Remove all the messages found from this.messages
			 */
			Iterator<Integer> indicesIter = messagesToReturnIndices.iterator();
			int numMessagesRemoved = 0;
			while(indicesIter.hasNext()){
				this.messages.remove(indicesIter.next().intValue()-numMessagesRemoved);
				numMessagesRemoved++;
			}
		}
		
		return messagesToReturn;
	}
	
	/**
	 * Returns the index (in this.messages) of the first message that has the same
	 * from & to elements has the msg.
	 * 
	 * If no such message is found, returns -1
	 * @param msg
	 * @return the message index if found, else -1
	 */
	private int getMessageIndex(Message msg){
		String from = msg.getFrom();
		String to = msg.getTo();
		
		Message curMessage = null;
		int numMessages = this.messages.size();
		
		for (int i=0;i<numMessages;i++){
			curMessage = this.messages.get(i);
			if (curMessage.getFrom().equals(from) &&
				curMessage.getTo().equals(to)){
				return i;
			}
		}
		
		return -1;
	}
	
	public String toString(){
		String out = "";
		
		Iterator<Message> messagesIter = this.messages.iterator();
		while(messagesIter.hasNext()){
			out += messagesIter.next().toString() + "\n";
		}
		
		return out;
	}
}
