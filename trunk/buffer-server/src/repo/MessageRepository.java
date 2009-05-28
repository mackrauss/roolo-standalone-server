package repo;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

/**
 * Singleton class that holds all the messages posted to the server
 * @author cfislotta
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
	 * Adds a new message to the repository. All duplicates are allowed
	 * @param msg
	 */
	public void addMessage(Message msg){
		// append to the end of the list
		
		this.messages.add(msg);
	}
	
	public void clear(){
		this.messages = new ArrayList<Message>();
	}
	
	/**
	 * Return a list of messages that are sent to "to"
	 * 
	 * @param to the to paramter to whom these messages should belong to
	 * 
	 * @return all messages directed at "to" user
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
	 * returns the next message directed at user "to". If found, returns the 
	 * message and deletes it from queue. If not found, returns null
	 * @param to
	 * @return
	 */
	public Message getNextMessage(String to){
		int numMessages = this.messages.size();
		Message curMessage = null;
		synchronized (this) {
			/*
			 * Find all the right messages and store their clones in messagesToReturn
			 */
			for(int i=0;i<numMessages;i++){
				curMessage = this.messages.get(i);
				if (curMessage.getTo().equals(to)){
					Message messageFound = curMessage.clone();
					this.messages.remove(i);
					return messageFound;
				}
			}
		}
		
		return null;
	}
	
	public Message getPeekMessage(String to){
		int numMessages = this.messages.size();
		Message curMessage = null;
		synchronized (this) {
			/*
			 * Find all the right messages and store their clones in messagesToReturn
			 */
			for(int i=0;i<numMessages;i++){
				curMessage = this.messages.get(i);
				if (curMessage.getTo().equals(to)){
					Message messageFound = curMessage.clone();
					//this.messages.remove(i);
					return messageFound;
				}
			}
		}
		
		return null;
	}
	
	public void replaceMessage (Message message){
		int numMessages = this.messages.size();
		Message curMessage = null;
		String from = message.getFrom();
		String to = message.getTo();
		synchronized (this) {
			/*
			 * Find all the messages with the same from & to, and delete them
			 */
			for(int i=numMessages-1;i>-1;i--){
				curMessage = this.messages.get(i);
				if (curMessage.getFrom().equals(from) && curMessage.getTo().equals(to)){
					this.messages.remove(i);
				}
			}
			
			this.messages.add(message);
		}
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
