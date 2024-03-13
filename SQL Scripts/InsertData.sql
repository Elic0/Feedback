CREATE PROCEDURE InsertFeedback
    @Mail TEXT,
    @AllowContact BIT,
    @Topic TEXT,
    @FromWebsite TEXT,
    @Feedback TEXT,
    @Images VARBINARY
AS
BEGIN
    INSERT INTO feedback (Mail, AllowContact, Topic, Browser, FromWebsite, UserOS, Feedback, Images)
    VALUES (@Mail, @AllowContact, @Topic, '', @FromWebsite, '', @Feedback, @Images);
END;
GO
