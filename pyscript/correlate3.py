#!/localdisk/anaconda3/bin/python
import sys
# get sys package for file arguments etc
import pymysql
import numpy as np
import seaborn as sns
import scipy.stats as sp
con = pymysql.connect(host='localhost', user='s2192822', passwd='123456', db='s2192822')
cur = con.cursor()
if(len(sys.argv) != 4) :
  print ("Usage: correlate.py col1 col2 (selection); Nparams = ",sys.argv)
  sys.exit(-1)

col1 = sys.argv[1]
col2 = sys.argv[2]
sel  = sys.argv[3]
sql = "SELECT %s,%s FROM Compounds where %s" % (col1,col2,sel)

cur.execute(sql)
nrows = cur.rowcount
ds = cur.fetchall()
ads = np.array(ds)
result = sp.pearsonr(ads[:,0],ads[:,1])
print ("<tr><td>{:.3f}</td>\n <td>{:.3f}</td> \n <td> {} </td> </tr>".format(result[0], result[1], nrows))
con.close()
