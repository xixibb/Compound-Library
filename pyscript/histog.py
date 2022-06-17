#!/localdisk/anaconda3/bin/python
import sys
# get sys package for file arguments etc
import pymysql
import numpy as np
import scipy.stats as sp
import matplotlib.pyplot as plt
import io
con = pymysql.connect(host='localhost', user='s2192822', passwd='123456', db='s2192822')
cur = con.cursor()
if(len(sys.argv) != 4) :
  print ("Usage: histog.py col name where ; Nparams = ",sys.argv)
  sys.exit(-1)

col1 = sys.argv[1]
col2 = sys.argv[3]
xname = sys.argv[2]
sql = "SELECT %s FROM Compounds where %s" % (col1,col2)
cur.execute(sql)
nrows = cur.rowcount
ds = cur.fetchall()
ads = np.array(ds)

# the histogram of the data

n, bins, patches = plt.hist(ads, density=0, facecolor='blue', alpha=0.5)
plt.xlabel(xname)
plt.ylabel('Count')
plt.title(f'Histogram of {xname}')
image = io.BytesIO()

plt.savefig(image,format='png')
sys.stdout.buffer.write(image.getvalue())

con.close()
