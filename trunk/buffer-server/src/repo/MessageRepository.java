package repo;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Queue;
import java.util.concurrent.ConcurrentLinkedQueue;

/**
 * Singleton class that holds all the messages posted to the server
 * @author cfislotta
 */
public class MessageRepository {
private static MessageRepository repo;
	
	/*
	 * Where all messages are kept
	 * String: the message's "to" field
	 * Queue: a queue of Message objects, all addressed to "to"
	 */
	private Map<String,Queue<Message>> messages;
	
	private MessageRepository(){
		this.messages = new HashMap<String,Queue<Message>>();
	}
	
	private Queue<Message> getNewQueue(){
		return new ConcurrentLinkedQueue<Message>();
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
		String toAddress = msg.getTo();
		
		//if recipient doesn't exist, create a queue for him
		if (!messages.containsKey(msg.getTo())){ 
			messages.put(toAddress, getNewQueue());	
		}
		
		//append the message to end of queue
		messages.get(toAddress).add(msg);
	}
	
	public void clear(){
		this.messages.clear();
	}
	
	/**
	 * Return a list of messages that are sent to "to"
	 * 
	 * @param to the to paramter to whom these messages should belong to
	 * 
	 * @return all messages directed at "to" user
	 */
	public List<Message> getMessages(String to){
		List<Message> messagesToReturn = new ArrayList<Message>();
		
		if (this.messages.containsKey(to)){
			// get all the messages to return
			messagesToReturn = new ArrayList<Message>(this.messages.get(to));
			
			// put an empty queue in its place
			this.messages.put(to,getNewQueue());
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
		if (this.messages.containsKey(to)){
			return this.messages.get(to).poll();
		}else{
			return null;
		}
	}
	
	public Message getPeekMessage(String to){
		if (this.messages.containsKey(to)){
			return this.messages.get(to).peek();
		}else{
			return null;
		}
	}
	
	public void replaceMessage (Message message){
		String toAddress = message.getTo();
		
		if (this.messages.containsKey(toAddress)){
			Queue<Message> q = this.messages.get(toAddress);
			Iterator<Message> qIter = q.iterator();
			int targetIndex = 0;
			while(qIter.hasNext()){
				Message curMessage = qIter.next();
				if (curMessage.getFrom().equals(message.getFrom())){
					Queue<Message> newQ = replaceElementInQueue(q, message, targetIndex);
					this.messages.put(toAddress, newQ);
					return;
				}
				targetIndex++;
			}
		}
		
		this.addMessage(message);
	}
	
	private Queue<Message> replaceElementInQueue(Queue<Message> q, Message message, int index){
		Queue<Message> sQueue = getNewQueue();
		this.transferNElements(q, sQueue, index);
		
		sQueue.add(message);
		q.poll();
		
		int remainderSize = q.size();
		this.transferNElements(q, sQueue, remainderSize);
		
		q = sQueue;
		
		return q;
		
//		Queue<Message> sQueue = getNewQueue();
//		this.transferNElements(q, sQueue, index);
//		
//		sQueue.add(message);
//		
//		int remainderSize = q.size();
//		this.transferNElements(q, sQueue, remainderSize);
		
	}
	
	private void transferNElements(Queue<Message> from, Queue<Message> to, int howMany){
		for(int i=0;i<howMany;i++){
			to.add(from.poll());
		}
	}
	
	public String toString(){
		StringBuilder outBuilder = new StringBuilder();
		
		Iterator<String> toIter = this.messages.keySet().iterator();
		String curTo = null;
		Queue<Message> curQ = null;
		Message curMessage = null;
		while(toIter.hasNext()){
			curTo = toIter.next();
			curQ = this.messages.get(curTo);
			outBuilder.append("TO: ==========").append(curTo).append("==========\n");
			
			Iterator<Message> qIter = curQ.iterator();
			while(qIter.hasNext()){
				curMessage = qIter.next();
				outBuilder.append("\t---------------------------------------------\n");
				outBuilder.append("\tFROM:  ").append(curMessage.getFrom()).append("\n");
				outBuilder.append("\tTYPE:  ").append(curMessage.getType()).append("\n");
				outBuilder.append("\tMESG:  ").append(curMessage.getContent()).append("\n");
				outBuilder.append("\t---------------------------------------------\n");
			}
			
			outBuilder.append("=================================================\n");
		}
		
		return outBuilder.toString();
	}
	
	public static void main(String[] args){
		Message m1 = new Message("f1", "t1", "tp1", "cont1");
		Message m2 = new Message("f2", "t2", "tp2", "cont2");
		Message m3 = new Message("f3", "t3", "tp3", "cont3");
		Message m11 = new Message("f1", "t1", "tp1", "cont4");
		
		MessageRepository repo = new MessageRepository();
		repo.addMessage(m1);
		repo.addMessage(m2);
		repo.addMessage(m3);
		repo.addMessage(m11);
		
		System.out.println(repo);
	}
	
	
//	private static MessageRepository repo;
//	
//	private List<Message> messages;
//	
//	private MessageRepository(){
//		this.messages = new ArrayList<Message>();
//	}
//	
//	public static MessageRepository getInstance(){
//		if (repo == null){
//			repo = new MessageRepository();
//		}
//		
//		return repo;
//	}
//	
//	/**
//	 * Adds a new message to the repository. All duplicates are allowed
//	 * @param msg
//	 */
//	public void addMessage(Message msg){
//		// append to the end of the list
//		
//		this.messages.add(msg);
//	}
//	
//	public void clear(){
//		this.messages = new ArrayList<Message>();
//	}
//	
//	/**
//	 * Return a list of messages that are sent to "to"
//	 * 
//	 * @param to the to paramter to whom these messages should belong to
//	 * 
//	 * @return all messages directed at "to" user
//	 */
//	public List getMessages(String to){
//		List messagesToReturn = new ArrayList<Message>();
//		List messagesToReturnIndices = new ArrayList<Integer>();
//		
//		int numMessages = this.messages.size();
//		Message curMessage = null;
//		synchronized (this) {
//			/*
//			 * Find all the right messages and store their clones in messagesToReturn
//			 */
//			for(int i=0;i<numMessages;i++){
//				curMessage = this.messages.get(i);
//				if (curMessage.getTo().equals(to)){
//					messagesToReturn.add(curMessage.clone());
//					messagesToReturnIndices.add(new Integer(i));
//				}
//			}
//			
//			/*
//			 * Remove all the messages found from this.messages
//			 */
//			Iterator<Integer> indicesIter = messagesToReturnIndices.iterator();
//			int numMessagesRemoved = 0;
//			while(indicesIter.hasNext()){
//				this.messages.remove(indicesIter.next().intValue()-numMessagesRemoved);
//				numMessagesRemoved++;
//			}
//		}
//		
//		return messagesToReturn;
//	}
//	
//	/**
//	 * returns the next message directed at user "to". If found, returns the 
//	 * message and deletes it from queue. If not found, returns null
//	 * @param to
//	 * @return
//	 */
//	public Message getNextMessage(String to){
//		int numMessages = this.messages.size();
//		Message curMessage = null;
//		synchronized (this) {
//			/*
//			 * Find all the right messages and store their clones in messagesToReturn
//			 */
//			for(int i=0;i<numMessages;i++){
//				curMessage = this.messages.get(i);
//				if (curMessage.getTo().equals(to)){
//					Message messageFound = curMessage.clone();
//					this.messages.remove(i);
//					return messageFound;
//				}
//			}
//		}
//		
//		return null;
//	}
//	
//	public Message getPeekMessage(String to){
//		int numMessages = this.messages.size();
//		Message curMessage = null;
//		synchronized (this) {
//			/*
//			 * Find all the right messages and store their clones in messagesToReturn
//			 */
//			for(int i=0;i<numMessages;i++){
//				curMessage = this.messages.get(i);
//				if (curMessage.getTo().equals(to)){
//					Message messageFound = curMessage.clone();
//					//this.messages.remove(i);
//					return messageFound;
//				}
//			}
//		}
//		
//		return null;
//	}
//	
//	public void replaceMessage (Message message){
//		int numMessages = this.messages.size();
//		Message curMessage = null;
//		String from = message.getFrom();
//		String to = message.getTo();
//		synchronized (this) {
//			/*
//			 * Find all the messages with the same from & to, and delete them
//			 */
//			for(int i=numMessages-1;i>-1;i--){
//				curMessage = this.messages.get(i);
//				if (curMessage.getFrom().equals(from) && curMessage.getTo().equals(to)){
//					this.messages.remove(i);
//				}
//			}
//			
//			this.messages.add(message);
//		}
//	}
//	
//	/**
//	 * Returns the index (in this.messages) of the first message that has the same
//	 * from & to elements has the msg.
//	 * 
//	 * If no such message is found, returns -1
//	 * @param msg
//	 * @return the message index if found, else -1
//	 */
//	private int getMessageIndex(Message msg){
//		String from = msg.getFrom();
//		String to = msg.getTo();
//		
//		Message curMessage = null;
//		int numMessages = this.messages.size();
//		
//		for (int i=0;i<numMessages;i++){
//			curMessage = this.messages.get(i);
//			if (curMessage.getFrom().equals(from) &&
//				curMessage.getTo().equals(to)){
//				return i;
//			}
//		}
//		
//		return -1;
//	}
//	
//	public String toString(){
//		String out = "";
//		
//		Iterator<Message> messagesIter = this.messages.iterator();
//		while(messagesIter.hasNext()){
//			out += messagesIter.next().toString() + "\n";
//		}
//		
//		return out;
//	}
}
