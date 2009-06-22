package repo;

import java.util.List;

import org.junit.After;
import org.junit.Assert;
import org.junit.Before;
import org.junit.Test;

/**
 * All test cases assume that the most basic get functionality works perfectly, otherwise there
 * would be no way to actually test things recursively. (MessageRepository.getNextMessage(String to))
 * 
 * The clear method is tested, but it has to be used in @Before, because MessageRepository is a singleton
 * and there is no way to recreate it
 * 
 * @author cfislotta
 *
 */
public class MessageRepositoryTest {
	
	private MessageRepository repo;
	
	@Before
	public void setup(){
		repo = MessageRepository.getInstance();
		repo.clear();
	}
	
	@After
	public void cleanup(){
		
	}
	
	@Test
	public void addMessage_oneMsg(){
		String[] m1Str = {"u1", "u2", "t1", "c1"};
		
		repo.addMessage(createMessage(m1Str));
		
		checkMessage(repo.getNextMessage("u2"), m1Str);
		Assert.assertNull(repo.getNextMessage("u2"));
	}
	
	@Test
	public void addMessage_twoMsgs(){
		String[] m1Str = {"u1", "u2", "t1", "c1"};
		String[] m2Str = {"u3", "u2", "t2", "c2"};
		
		repo.addMessage(createMessage(m1Str));
		repo.addMessage(createMessage(m2Str));
		
		checkMessage(repo.getNextMessage("u2"), m1Str);
		checkMessage(repo.getNextMessage("u2"), m2Str);
	}
	
	@Test
	public void addMessage_oneMsgTwoReceivers(){
		String[] m1Str = {"u1", "u2", "t1", "c1"};
		String[] m2Str = {"u3", "u4", "t2", "c2"};
		
		repo.addMessage(createMessage(m1Str));
		repo.addMessage(createMessage(m2Str));
		
		checkMessage(repo.getNextMessage("u2"), m1Str);
		checkMessage(repo.getNextMessage("u4"), m2Str);
	}
	
	@Test
	public void clear_noMsgs(){
		repo.clear();
	}
	
	@Test
	public void clear_oneMsg(){
		String[] m1Str = {"u1", "u2", "t1", "c1"};
		
		repo.addMessage(createMessage(m1Str));
		
		repo.clear();
		
		Assert.assertNull(repo.getNextMessage("u2"));
	}
	
	@Test
	public void getMessages_noMsg(){
		List msgs = repo.getMessages("u2");
		
		Assert.assertNotNull(msgs);
		Assert.assertEquals(0, msgs.size());
	}
	
	@Test
	public void getMessages_oneMsg(){
		String[] m1Str = {"u1", "u2", "t1", "c1"};
		
		repo.addMessage(createMessage(m1Str));
		
		List<Message> msgs = repo.getMessages("u2");
		
		Assert.assertEquals(1, msgs.size());
		checkMessage(msgs.get(0), m1Str);
		Assert.assertNull(repo.getNextMessage("u2"));
	}
	
	@Test
	public void getMessages_twoMsgs(){
		String[] m1Str = {"u1", "u2", "t1", "c1"};
		String[] m2Str = {"u3", "u2", "t2", "c2"};
		
		repo.addMessage(createMessage(m1Str));
		repo.addMessage(createMessage(m2Str));
		
		List<Message> msgs = repo.getMessages("u2");
		
		Assert.assertEquals(2, msgs.size());
		checkMessage(msgs.get(0), m1Str);
		checkMessage(msgs.get(1), m2Str);
		Assert.assertNull(repo.getNextMessage("u2"));
	}
	
	@Test
	public void getPeekMessage_noMsg(){
		Assert.assertNull(repo.getPeekMessage("u2"));
	}
	
	@Test
	public void getPeekMessage_oneMsg(){
		String[] m1Str = {"u1", "u2", "t1", "c1"};
		
		repo.addMessage(createMessage(m1Str));
		
		checkMessage(repo.getPeekMessage("u2"), m1Str);
		
		//make sure the message is still there
		checkMessage(repo.getNextMessage("u2"), m1Str);
	}
	
	@Test
	public void getPeekMessage_twoMsgs(){
		String[] m1Str = {"u1", "u2", "t1", "c1"};
		String[] m2Str = {"u3", "u2", "t2", "c2"};
		
		repo.addMessage(createMessage(m1Str));
		repo.addMessage(createMessage(m2Str));
		
		checkMessage(repo.getPeekMessage("u2"), m1Str);
		
		//make sure both messages are there still
		List<Message> msgs = repo.getMessages("u2");
		
		Assert.assertEquals(2, msgs.size());
		checkMessage(msgs.get(0), m1Str);
		checkMessage(msgs.get(1), m2Str);
	}
	
	@Test
	public void replaceMessage_noMsg(){
		String[] m1Str = {"u1", "u2", "t1", "c1"};
		
		repo.replaceMessage(createMessage(m1Str));
		
		checkMessage(repo.getNextMessage("u2"), m1Str);
	}
	
	@Test
	public void replaceMessage_replacingOneMsgThatExists(){
		String[] m1Str = {"u1", "u2", "t1", "c1"};
		String[] m2Str = {"u1", "u2", "t2", "c2"};
		
		repo.addMessage(createMessage(m1Str));
		repo.replaceMessage(createMessage(m2Str));
		
		checkMessage(repo.getNextMessage("u2"), m2Str);
		
		//make sure there is nothing left
		Assert.assertNull(repo.getNextMessage("u2"));
	}
	
	@Test
	public void replaceMessage_replacingFirstMsgInManyMsgs(){
		String[] m1Str = {"u1", "u2", "t1", "c1"};
		String[] m2Str = {"u3", "u2", "t2", "c2"};
		String[] m3Str = {"u4", "u2", "t3", "c3"};
		String[] m4Str = {"u5", "u2", "t4", "c4"};
		
		String[] m1rStr = {"u1", "u2", "t1r", "c1r"};
		
		repo.addMessage(createMessage(m1Str));
		repo.addMessage(createMessage(m2Str));
		repo.addMessage(createMessage(m3Str));
		repo.addMessage(createMessage(m4Str));
		
		repo.replaceMessage(createMessage(m1rStr));
		
		//the first message should be the one we need
		checkMessage(repo.getNextMessage("u2"), m1rStr);
		
		//three more messages in proper order
		checkMessage(repo.getNextMessage("u2"), m2Str);
		checkMessage(repo.getNextMessage("u2"), m3Str);
		checkMessage(repo.getNextMessage("u2"), m4Str);
	}
	
	@Test
	public void replaceMessage_replacingLastMsgInManyMsgs(){
		String[] m1Str = {"u1", "u2", "t1", "c1"};
		String[] m2Str = {"u3", "u2", "t2", "c2"};
		String[] m3Str = {"u4", "u2", "t3", "c3"};
		String[] m4Str = {"u5", "u2", "t4", "c4"};
		
		String[] m4rStr = {"u5", "u2", "t4r", "c4r"};
		
		repo.addMessage(createMessage(m1Str));
		repo.addMessage(createMessage(m2Str));
		repo.addMessage(createMessage(m3Str));
		repo.addMessage(createMessage(m4Str));
		
		repo.replaceMessage(createMessage(m4rStr));
		
		checkMessage(repo.getNextMessage("u2"), m1Str);
		checkMessage(repo.getNextMessage("u2"), m2Str);
		checkMessage(repo.getNextMessage("u2"), m3Str);
 		checkMessage(repo.getNextMessage("u2"), m4rStr);
	}
	
	@Test
	public void replaceMessage_replacingNonexistMsgInManyMsgs(){
		String[] m1Str = {"u1", "u2", "t1", "c1"};
		String[] m2Str = {"u3", "u2", "t2", "c2"};
		String[] m3Str = {"u4", "u2", "t3", "c3"};
		String[] m4Str = {"u5", "u2", "t4", "c4"};
		
		String[] m5rStr = {"u6", "u2", "t4r", "c4r"};
		
		repo.addMessage(createMessage(m1Str));
		repo.addMessage(createMessage(m2Str));
		repo.addMessage(createMessage(m3Str));
		repo.addMessage(createMessage(m4Str));
		
		repo.replaceMessage(createMessage(m5rStr));
		
		checkMessage(repo.getNextMessage("u2"), m1Str);
		checkMessage(repo.getNextMessage("u2"), m2Str);
		checkMessage(repo.getNextMessage("u2"), m3Str);
 		checkMessage(repo.getNextMessage("u2"), m4Str);
 		checkMessage(repo.getNextMessage("u2"), m5rStr);
	}
	
	/************************************
	 * 				HELPERS				*
	 ************************************/
	private void printRepo(){
		System.out.println(this.repo);
	}
	
	private Message createMessage(String[] fields){
		return new Message(fields[0], fields[1], fields[2], fields[3]);
	}
	
	private void checkMessage(Message m, String[] mStr){
		Assert.assertEquals(mStr[0], m.getFrom());
		Assert.assertEquals(mStr[1], m.getTo());
		Assert.assertEquals(mStr[2], m.getType());
		Assert.assertEquals(mStr[3], m.getContent());
	}
}
