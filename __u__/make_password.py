import sys, hashlib, random
if len(sys.argv)!=2:
    sys.stderr.write("usage: %s <password>\n will return salt and hashed password.\n $ %s test > user_id/password\n"%(sys.argv[0], sys.argv[0]))
    sys.exit(22)
password=sys.argv[1]
salt=hashlib.md5(str(random.random()).encode("ascii")).hexdigest()
hash=hashlib.sha512((password+salt).encode("ascii")).hexdigest()
sys.stdout.write(salt+"\n"+hash+"\n")