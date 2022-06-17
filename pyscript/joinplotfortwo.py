#!/localdisk/anaconda3/bin/python
import sys
# get sys package for file arguments etc
import pymysql
import numpy as np
import seaborn as sns
import matplotlib.pyplot as plt
import scipy.stats as sp
import io

con = pymysql.connect(host='localhost', user='s2192822', passwd='123456', db='s2192822')
cur = con.cursor()
if(len(sys.argv) != 6) :
  print ("Usage: joinplotfortwo.py col1 col1-label col2 col2-label where-condition",sys.argv)
  sys.exit(-1)


#read arguments
col1 = sys.argv[1]
col1_label = sys.argv[2]
col2 = sys.argv[3]
col2_label = sys.argv[4]
cond = sys.argv[5]


#get data from database

sql_query = f"select {col1}, {col2} from Compounds where {cond}"

cur.execute(sql_query)
nrows = cur.rowcount
ds = cur.fetchall()
ads = np.array(ds)
corr = sp.pearsonr(ads[:,0],ads[:,1])
g = sns.JointGrid(x=ads[:,0], y=ads[:,1])
g = g.plot_joint(plt.scatter, color="#5CDB95", edgecolor="white")
_ = g.ax_marg_x.hist(ads[:,0], color="#05386B", alpha=.6)
_ = g.ax_marg_y.hist(ads[:,1], color="#F64C72", alpha=.6, orientation="horizontal")
plt.xlabel(col1_label)
plt.ylabel(col2_label)
image = io.BytesIO()

plt.savefig(image,format='png', bbox_inches='tight')
sys.stdout.buffer.write(image.getvalue())

con.close()

