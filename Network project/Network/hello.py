
#!/usr/bin/python

import mysql.connector
from mysql.connector import errorcode

import os
import subprocess
import time

config = {
  'user': 'root',
  'password': '',
  'host': '127.0.0.1',
  'database': 'network',
  'raise_on_warnings': True,
}

def call_download(url, filename, cursor, cnx):
           os.system("IDMan.lnk /n /p C:\wamp\www\Network\data /d %s"%(url))
           print "IN download"
           while(1):
               print "checking for the file"
               if os.path.exists("C:\wamp\www\Network\data\%s"%(filename)):
                   break
                
           updatesql = "UPDATE `downloads` SET `status`=1,`priority`=201 WHERE `url` = '%s' "%(url)
           print(updatesql)
           cursor.execute(updatesql)
           cnx.commit()

try:
  cnx = mysql.connector.connect(**config)
except mysql.connector.Error as err:
  if err.errno == errorcode.ER_ACCESS_DENIED_ERROR:
    print("Something is wrong with your user name or password")
  elif err.errno == errorcode.ER_BAD_DB_ERROR:
    print("Database does not exists")
  else:
    print(err)

    
# prepare a cursor object using cursor() method
cursor = cnx.cursor()
while 1:
    while 1 :
                    # Prepare SQL query to INSERT a record into the database.
                    readsql = "SELECT * FROM status WHERE kindof = 0 "
                    cursor.execute(readsql)
                    toread = cursor.fetchone()

                    if toread[1] == 0:
                            time.sleep(10)
                            print (toread[1])
                            continue;
                            #sleep for some time
                    else :
                            if toread[1] == -1:
                                    time.sleep(20)
                                    continue
                            else:
                                    break

    sql = "SELECT * FROM  downloads WHERE priority < 201"
    #print 'hi how re u'
    try:
       #os.system("idman.lnk /n /d http://www.fanpop.com/clubs/one-piece/images/16074133/title/luffy-ace-photo")
       # Execute the SQL command
       cursor.execute(sql)
       # Fetch all the rows in a list of lists.
       results = cursor.fetchall()
       a = "http://intranet.iith.ac.in/software/scientific/scilab/scilab-5.2.1.exe"
       for row in results:
           #downcmd = "IDMan.lnk /n /p C:\wamp\www\Network\data /f {} /d {} /n".format(name, url)
           #print ("IDMan.lnk /n /p C:\wamp\www\Network\data /d %s"%(row[1]))
            call_download(a,"Tru",cursor,cnx)
            print  "adfadsfadsfasdf"
#          os.system("IDMan.lnk /n /p C:\wamp\www\Network\data /d %s"%(row[1]))
#          updatesql = "UPDATE `downloads` SET `status`=1,`priority`=201 WHERE `url` = '%s' "%(row[1])
#          print(updatesql)
#          cursor.execute(updatesql)
#          cnx.commit()
    except:
       print "Error: unable to fecth data"
    updatesql = "UPDATE `status` SET `value`=0 WHERE `kindof` = 0"
    print(updatesql)
    cursor.execute(updatesql)
    cnx.commit()

# disconnect from server
cnx.close()
