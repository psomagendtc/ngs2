import sys, smtplib, base64
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.header import Header

if len(sys.argv)!=5:
	sys.stderr.write("usage: %s <gmail_id> <gmail_pw> <email> <url>\n"%(sys.argv[0]))
	sys.exit(22)
gmail_id, gmail_pw, receiver_email, url=sys.argv[1:5]

def send(receiver_email, subject, html):
	global gmail_id, gmail_pw
	server, port, displayname="smtp.gmail.com", 465, "Psomagen"
	s=smtplib.SMTP_SSL(server, port)
	s.ehlo()
	s.login(gmail_id, gmail_pw)
	msg=MIMEMultipart("alternative")
	msg["Subject"]=Header(subject, "utf-8")
	msg["From"]="%s <%s>"%(displayname, gmail_id)
	msg["To"]=receiver_email
	msg.attach(MIMEText(html, "html"))
	s.sendmail(gmail_id, [receiver_email], msg.as_string())
	s.close()

send(receiver_email, "Reset Account Password", """<div style="width:100%%;background-color:#cccccc;padding:100px 0;">
<div style="width:500px;margin:auto;padding:30px;border-radius:3px;box-shadow:1px 1px 5px #999999;background-color:#eeeeee;">
	<div style="font-size:18px;font-weight:bold;line-height:32px;line-height:31px;color:#111111;text-align:center;">Reset Account Password</div>
	<div style="width:100%%;height:1px;margin:10px 0;background-color:#999999;"></div>
	<div style="font-size:16px;font-weight:bold;line-height:36px;color:#333333;">Dear customer,</div>
	<div style="font-size:14px;line-height:22px;color:#333333;">We're sending you this email because you requested a password set or reset.<br/>Click on this link to set a new password:</div>
	<div style="padding:20px 0;text-align:center;"><a href="%s"><button style="width:300px;padding:16px;background-color:navy;color:white;font-size:16px;line-height:16px;font-weight:bolder;border-radius:24px;cursor:pointer;">Create a new password</button></a></div>
	<div style="font-size:14px;line-height:22px;color:#333333;">If you didn't request the password reset, you can ignore this email.<br/>Your password will not be changed.</div>
	<div style="font-size:16px;font-weight:bold;line-height:48px;color:navy;text-align:center;">Psomagen</div>
</div>
</div>"""%(url))