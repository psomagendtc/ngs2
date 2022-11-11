import sys, smtplib, base64
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.header import Header

if len(sys.argv)!=2:
	sys.stderr.write("usage: %s <email>\n"%(sys.argv[0]))
	sys.exit(22)
receiver_email=sys.argv[1]

def send(receiver_email, subject, html):
	server, port, displayname, sender, password="smtp.gmail.com", 465, "Psomagen", base64.b16decode(b"6E6F2D7265706C79406B65616E6865616C74682E636F6D").decode(), base64.b16decode(b"787A666E676268737178726F616B7A62").decode()
	s=smtplib.SMTP_SSL(server, port)
	s.ehlo()
	s.login(sender, password)
	msg=MIMEMultipart("alternative")
	msg["Subject"]=Header(subject, "utf-8")
	msg["From"]="%s <%s>"%(displayname, sender)
	msg["To"]=receiver_email
	msg.attach(MIMEText(html, "html"))
	s.sendmail(sender, [receiver_email], msg.as_string())
	s.close()

send("uugi0620@gmail.com", "Test", "Hello")