CREATE TABLE feedback (
	ID INTEGER PRIMARY KEY,
	Mail TEXT,
	AllowContact BIT,
	Topic TEXT,
	Browser TEXT,
	FromWebsite TEXT,
	UserOS TEXT,
	Feedback TEXT,
	Images varbinary
);
