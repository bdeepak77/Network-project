import subprocess
import os
import mysql.connector
from mysql.connector import errorcode
config = {
  'user': 'root',
  'password': '',
  'host': '127.0.0.1',
  'database': 'Network',
  'raise_on_warnings': True,
}

try:
  cnx = mysql.connector.connect(**config)
except mysql.connector.Error as err:
  if err.errno == errorcode.ER_ACCESS_DENIED_ERROR:
    print("Something is wrong with your user name or password")
  elif err.errno == errorcode.ER_BAD_DB_ERROR:
    print("Database does not exists")
  else:
    print(err)

cursor = cnx.cursor()
sqlcmd = "SELECT name, url, MIN(priority) AS minprio FROM downloads"
cursor.execute(sqlcmd)

for (name, url, minprio) in cursor:
  downcmd = "IDMan.lnk /n /p C:\wamp\www\Network\data /f {} /d {} /n".format(name, url)
  print (downcmd)
  os.system(downcmd)
  print("{}, {}".format( name, url))

cursor.close()
cnx.close()
