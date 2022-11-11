import sys
if len(sys.argv)!=2:
	sys.stderr.write("usage: %s <email>\n"%(sys.argv[0]))
	sys.exit(22)
email=sys.argv[1]
print(email)













"""
import os, sys
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
import common, smtplib, json, time
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.header import Header

interval_time=2

#Configurations
with open(os.path.abspath(os.path.join(os.path.dirname(__file__), "..", "..", "..", "email.config.json")), "r") as f:_CONFIGS=json.load(f)

#Mailer class to be initiated and be called send function to send the email
class Mailer:
    def __init__(self):
        global _CONFIGS
        self.setup=_CONFIGS
        self.s=None
        self.lasttime=time.time()-1
    def __del__(self):
        if self.s!=None:
            self.s.close()
    def send(self, receiver_email, subject, html):
        global interval_time
        starttime=time.time()
        while time.time()-self.lasttime<interval_time:
            time.sleep(0.01)
        if self.s==None:
            self.sender=self.setup["sender"]
            self.s=smtplib.SMTP_SSL(self.setup["smtp_server"], self.setup["smtp_port"])
            #self.s.set_debuglevel(1)
            self.s.ehlo()
            self.s.login(self.sender, self.setup["password"])
        receivers=[receiver_email]
        msg=MIMEMultipart("alternative")
        msg["Subject"]=Header(subject, "utf-8")
        msg["From"]="%s <%s>"%(self.setup["display_name"], self.sender)
        msg["To"]=receiver_email
        msg.attach(MIMEText(html, "html"))
        self.s.sendmail(self.sender, receivers, msg.as_string())
        self.lasttime=starttime

email_list_status, email_list=common.call("GET", "/email/list/", {"state":"N"})
def post_email_state(idx, state):
    return common.call("POST", "/email/state/", {"idx":idx, "state":state})
if email_list_status==200:
    mailer=Mailer()
    for email in email_list:
        idx, receiver, title, content=email["idx"], email["receiver"], email["title"], email["content"]
        post_email_state(idx, "G")
        try:
            mailer.send(receiver, title, content)
            post_email_state(idx, "Y")
        except Exception as e:
            print(e, file=sys.stderr)
            post_email_state(idx, "E")
"""