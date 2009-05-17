package repo;

public class Message {
	private String from;
	private String to;
	private String type;
	private String content;
	
	public Message(String from, String to, String type, String content){
		this.from = from;
		this.to = to;
		this.type = type;
		this.content = content;
	}

	public String getFrom() {
		return from;
	}

	public void setFrom(String from) {
		this.from = from;
	}

	public String getTo() {
		return to;
	}

	public void setTo(String to) {
		this.to = to;
	}

	public String getType() {
		return type;
	}

	public void setType(String type) {
		this.type = type;
	}

	public String getContent() {
		return content;
	}

	public void setContent(String content) {
		this.content = content;
	}
	
	public String toString(){
		return "from: " + this.from + " to: " + this.to + " type: " + this.type + " content: " + this.content;
	}
	
	public String toXml(){
		return "<message> <from>" + this.from + "</from> <to>" + this.to + "</to> <type>" + this.type + "</type> <content>" + this.content + "</content> </message>";
	}
	
	public Message clone(){
		return new Message(this.from, this.to, this.type, this.content); 
	}
}
