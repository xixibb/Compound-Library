#!/localdisk/anaconda3/bin/python

# import sys to obtain user arguments
import sys 
# import numpy to do numeric computations
import numpy as np 
# import io to handle input string as a file
import io
# import pyplot to plot pictures
from matplotlib import pyplot as plt
# import sql to read data
import pymysql
# import base64 to decode sdf
import base64
# import pandas to store results
import pandas as pd

class MolDescriptor:
    '''
    This class provides methods for computing USR descriptor for a given molecule sdf
    '''

    def __init__(self, name, mol_sdf, show_log=False):
        # Initialize class object by specifying a molecule [mol]
        # [show_log]: a parameter to control if print logs of class methods
        #             the default value of showing log is true
        self.name = name
        self.read_sdf(mol_sdf) # store [mol] in the class attribute [mol]
        self.show_log = show_log # store [show_log] in the class attribute [show_log]


    #################
    # Basic Methods #
    #################
    
    def read_sdf(self, sdf_string):
        sdf_string = base64.b64decode(sdf_string).decode('utf-8')
        f = io.StringIO(sdf_string)
        lines = f.readlines()
        
        self.n_atm =  int(list(filter(None, lines[3].split(' ')))[0].strip('\n'))
        
        atoms = []
        pos_list = []
        for line in lines[4:4+self.n_atm]:
            all_data = list(filter(None, line.split(' ')))
            pos = [float(i) for i in all_data[:3]]
            pos_list.append(pos)
            atoms.append(all_data[3])
        
        self.pos_list = pos_list
        self.atoms = atoms
        f.close()

    def get_euclidean_distance(self, from_pos, to_pos):
        '''
        Return the euclidean distance between [from_pos] and [to_pos]
        '''
        # The formula of euclidean distance between x and y is given by
        # the square root of the sum of (x_i - y_i)^2

        euc_dist = 0 # create a variable to be 0
        
        for i in range(len(from_pos)):
            euc_dist += (from_pos[i] - to_pos[i])**2 # compute the sum of (x_i - y_i)^2
        
        euc_dist = np.sqrt(euc_dist) # take the square root of this sum to get euclidean distance
        
        return euc_dist # return the distance we want


    def get_center_of_gravity(self):
        '''
        Return the center of gravity (COG) of molecule [mol]
        The center of gravity is the mean coordinates of all atoms inside molecule (excluding hydrogens)
        The exclusion of hydrogens is implemented when reading molecule from SD files
        so there is no such exclusion inside this method
        '''
        
        coords = np.array(self.pos_list) # convert to numpy array
        sum_weights = np.sum(coords, axis=0) # get the sum of coordinates over all atoms 
        # center of gravity = weights/number_of_atoms
        # convert the numpy array back to list for later use
        center_of_gravity = list(sum_weights/self.n_atm) 

        
        if self.show_log: # if show log
            # log information to be printed
            # a formated string is used to improve readibility
            # if the name of molecule is AAA and its COG is [1,1,1]
            # then the user can observe a sample log:
            #   The center of gravity in AAA is [1,1,1]
            print(f"The center of gravity is {center_of_gravity}")
        
        return center_of_gravity # return the location of COG 


    def get_closest_atom_pos_to(self, ref_pos="COG"):
        '''
        Return the closest atom position to the given reference position [ref_pos]
        if [ref_pos] is not specified, the default reference position is COG
        ''' 
        
        if ref_pos == "COG": # the default value of [ref_pos] when it is not specified by users
            # "COG" is a string not containing any location information
            # so we need to replace [ref_pos] by the COG location
            # COG location can be found by the class method [get_center_of_gravity] defined above
            ref_pos = self.get_center_of_gravity(self.mol)
        
        # the remaining part of this function is used to find the closest atom position
        # the approach is to iterate over all atoms in the molecule
        # and compute the distance [current_distance] between [atom] and [ref_pos]
        # if the distance is smaller than the best known distance [distance]
        # current atom is the closest atom found so far
        # replace the best known distance [distance] by [current_distance]
        
        
        distance = None # [distance] is used to store the shorest distance 
                        # among all these between atoms and [ref_pos]
        closest_pos = None # [closest_pos] is used to store the position of the closest atom
        closest_atom = None # [closest_atom] is used to store the closest atom

        # All three variables above are initiated to be None 
        # which is a special Python reserved character meaning "Nothing"
        # Actually these variables will soon be assigned to be related to the first atom in the molecule
        # Such assignment is done in the for-loop after verifying these variables are None
        # After being assigned, these variables are no longer None and 
        # so the initial values None can serve as an identifier of "initial state"

        for icn in range(self.n_atm): # iterate over all atoms one by one in the molecule
            pos = self.pos_list[icn] # get postiion of the current atom
            # compute the distance between current atom and [ref_pos]
            current_distance = self.get_euclidean_distance(pos, ref_pos) 
            
            # There are two cases in which [distance], [closest_pos], and [closest_atom] will be updated
            # The first case is when all of them are None, then let them equal to 
            # the values related to current atom (the first one).
            # The second case is when the current atom has a shorter distance than all processed before.
            # so current atom is the closest atom known so far,  [distance], [closest_pos], and [closest_atom]
            # will be changed to its values
            if distance is None or current_distance < distance: 
                
                # update [distance] to be the distance between current atom and [ref_pos]
                distance = current_distance 
                closest_pos = pos # update [closest_pos] to be the current atom position
                closest_atom = self.atoms[icn] # update [closest_atom] to be the current atom
        
        if self.show_log: # if print log
            # if the name of molecule is AAA, reference position is [1,1,1], 
            # and cloest atom is C at [1.1, 1.1, 0.9],
            # then the sample log is
            #     The closest atom in molecule AAA to location [1,1,1] is C at [1.1, 1.1, 0.9]"
            print("The closest atom in molecule to "\
                + f"location {ref_pos} is {closest_atom} " \
                + f"at {[closest_pos[0], closest_pos[1], closest_pos[2]]}")
        
        return list(closest_pos) # return the position of the closest atom to [ref_pos]


    def get_furthest_atom_pos_from(self, ref_pos="COG"):
        '''
        Return the furthest atom position to the given reference position [ref_pos]
        if [ref_pos] is not specified, the default reference position is COG
        '''

        if ref_pos == "COG": # the default value of [ref_pos] when it is not specified by users
            # "COG" is a string not containing any location information
            # so we need to replace [ref_pos] by the COG location
            # COG location can be found by the class method [get_center_of_gravity] defined above
            ref_pos = self.get_center_of_gravity()
        
        # the remaining part of this function is used to find the furthest atom position
        # the approach is to iterate over all atoms in the molecule
        # and compute the distance [current_distance] between [atom] and [ref_pos]
        # if the distance is smaller than the best known distance [distance]
        # current atom is the furthest atom found so far
        # replace the best known distance [distance] by [current_distance]


        distance = None # [distance] is used to store the longest distance 
                        # among all these between atoms and [ref_pos]
        furthest_pos = None # [furthest_pos] is used to store the position of the furthest atom
        furthest_atom = None # [furthest_atom] is used to store the furthest atom
        
        for icn in range(self.n_atm): # iterate over all atoms one by one in the molecule
            pos = self.pos_list[icn] # get postiion of the current atom
            # compute the distance between current atom and [ref_pos]
            current_distance = self.get_euclidean_distance(pos, ref_pos) 
            
            # There are two cases in which [distance], [furthestpos], and [furthest_atom] will be updated
            # The first case is when all of them are None, then let them equal to 
            # the values related to current atom (the first one).
            # The second case is when the current atom has a longer distance than all processed before.
            # so current atom is the furthest atom known so far,  [distance], [furthest_pos], and [furthest_atom]
            # will be changed to its values

            if distance is None or current_distance > distance:
                distance = current_distance # update [distance] to be the distance between current atom and [ref_pos]
                furthest_pos = pos # update [furthest_pos] to be the current atom position
                furthest_atom = self.atoms[icn] # update [furthest_atom] to be the current atom
    
        
        if self.show_log: # if print log
            # if the name of molecule is AAA, reference position is [1,1,1], and furthest atom is C at [7, 8, 9],
            # then the sample log is
            #     The furthest atom in molecule AAA to location [1,1,1] is C at [7, 8, 9]"
            print(f"The furthest atom to "\
                + f"location {ref_pos} is {furthest_atom} " \
                + f"at {[furthest_pos[0], furthest_pos[1], furthest_pos[2]]}")
        
        return list(furthest_pos) # return the position of the furthest atom to [ref_pos]


    def get_atom_distance_list(self, ref_pos):
        '''
        This method get a list of distances between atoms in molecule [mol] to [ref_pos]
        this list is necessary to compute statistics and USR descriptor vector
        '''

        dist_list = [] # initialize the output distance list to be empty
        icn = 0 # initialize the cursor pointing to the first atom
        for icn in range(self.n_atm): # iterate over all atoms one by one in the molecule
            pos = self.pos_list[icn] # get postiion of the current atom
            # compute the distance between current atom and [ref_pos]
            current_distance = self.get_euclidean_distance(pos, ref_pos)
            dist_list.append(current_distance) # add the evaluated distance to the output list [dist_list]
            icn += 1 # move the cursor to the next atom
        
        return dist_list # return the distance list [dist_list]


    ################
    # STAT METHODS #
    ################


    def get_mean(self, dl):
        '''
        Return the mean value of the input list
        '''
        
        # the mean is calculated by using numpy.mean method
        dl_mean = np.mean(dl)
        
        return dl_mean


    def get_std(self, dl):
        '''
        Return the standard deviation of the input list
        the standard deviation is calculated by using numpy.std method
        the standard deviation is the square root of variance
        the variance is computed by sum_i(x_i - mean)^2 / (n - ddof)
        where n is the number of samples,
        [ddof] is a paramter to specify the degree of freedom
        '''

        # to get the sample variance, ddof needs to be 1
        dl_std = np.std(dl, ddof=1)
        
        return dl_std


    def get_cubic_skew(self, dl):
        '''
        Return the cubic root of skewness of the input list
        the sample skewness is computed by dividing sum_i [(x_i - mean) / (std.var)]^3 by n-1
        where n is the number of samples
        '''

        dl_mean = self.get_mean(dl) # get mean value by using the method get_mean
        dl_std = self.get_std(dl) # get standard deviation by using the method get_std
        cubic_sum = np.sum(((dl - dl_mean)/dl_std)**3) 
        dl_skew = cubic_sum/(len(dl) - 1)
        cubic_skew = np.power(abs(dl_skew), 1/3)
        
        cubic_skew = - cubic_skew if dl_skew < 0 else cubic_skew

        return dl_skew 


    def get_stats_of_pos(self, ref_pos):
        '''
        This method outputs the triplet statistics for the distances
        between atoms and the given reference position [ref_pos]
        The three statistics are:
            1. mean
            2. standrad deviation
            3. a cubic root of the skewness 
        '''

        dist_list = self.get_atom_distance_list(ref_pos) # get the distance list of the invoking 
                                                         # molecule [mol] to [ref_pos]
        stat_dict = {
            "mean": self.get_mean(dist_list), # get mean distance
            "std": self.get_std(dist_list), # get standard deviation of distances
            "cubic_skew": self.get_cubic_skew(dist_list), # get cubic root of skewness of distances
        }
        
        return stat_dict # return stat dictionary


    ###############
    # Descriptors #
    ###############


    def get_usr(self):
        '''
        This method provides a simple and convenient way to 
        obtain the 12 double set for the invoking mol [mol]
        These 12 numbers are further divided into 4 triples each of which is made of
        three statistics of the distances between atoms to a given point selected from the following:
        1. center of gravity (COG)
        2. the closest atom position to COG 
        3. the furtherest atom position to COG
        4. the furtherest atom position to position 3 found above
        These 12 numbers forming a vector which is usually called USR descriptor
        
        Reference: Ultrafast shape recognition (USR) to search 
        compound databases for similar molecular shapes. Pedro J. Ballester *, 
        W. Graham Richards Journal of Computational chemistry Vol 28(10) 1711-1723.
        '''

        pos_1 = self.get_center_of_gravity() # COG
        pos_1_stat = self.get_stats_of_pos(pos_1) # get triple distance statistics with respect to COG
        
        pos_2 = self.get_closest_atom_pos_to(pos_1) # get the closest atom position to COG
        pos_2_stat = self.get_stats_of_pos(pos_2) # get triple distance statistics with respect to [pos_2]
        
        pos_3 = self.get_furthest_atom_pos_from(pos_1) # get the furthest atom position to COG
        pos_3_stat = self.get_stats_of_pos(pos_3) # get triple distance statistics with respect to [pos_3]
        
        pos_4 = self.get_furthest_atom_pos_from(pos_3) # get the furthest atom position to [pos_3]
        pos_4_stat = self.get_stats_of_pos(pos_4) # get triple distance statistics with respect to [pos_3]
        
        # assemble these 12 values into a single list
        # triple statistics are stored as dictionaries
        # however only their values are needed and so use [pos_?_stat.values()] to retrieve its values
        stat_of_mol = list(pos_1_stat.values()) + list(pos_2_stat.values())\
                     + list(pos_3_stat.values()) + list(pos_4_stat.values())
        
        return stat_of_mol

#---------------------------
# END OF CLASS MolDescriptor
#---------------------------


class SimilarityTool:
    '''
     this is a utils class
     it contains methods to calculate similarity between molecules
     also three methods for visualising purpose
     the reason to put all these methods into a class instead of listing them outside
     is that other python scripts can access these methods easily by importing this class
    '''

    def get_similarity_from_usr(self, des_1, des_2):
        '''
        return similarity score of two USR descriptors (vectors)
        the similarity is computed as the follows:
        [similarity] = 1 / (1 + [sum_of_abs]/12) 
        where [sum_of_abs] = the sum of absolute pairwise difference
        between [des_1] and [des_2]
        '''

        assert(len(des_1) == 12) # the length of usr descriptor must be 12
        assert(len(des_2) == 12) # the length of usr descriptor must be 12

        # compute pairwise difference between [des_1] and [des_2]
        # and store in numpy.array [diff_array]
        # [des_1] and [des_2] are firstly converted to numpy.array
        # because this is a more convenient way to do pairwise subtraction
        # (without using for-loop)

        diff_array = np.array(des_1) - np.array(des_2)

        # get the sum of absolute values of [diff_array] 
        sum_of_abs = np.sum(np.abs(diff_array))
        
        # get the similarity score
        similarity = 1/(1+ (sum_of_abs)/12)
            
        return similarity


# ---------------- EXECUATE ------------

# search for the most similar term
if len(sys.argv) !=3 and len(sys.argv)!=4:
  print("Usage: similar.py ref_sdf supplier_cond Similarity-Cut-Off(Optional)")
  sys.exit(-1)

# similarity-cut-off is left here for future improvement
# due to the limited time, similarity-cut-off has not been used
ref_sdf = sys.argv[1]
cond = sys.argv[2]
if len(sys.argv) == 4:
  sim_cut = sys.argv[3]
else:
  sim_cut = 0

# get input reference molecule
ref_mol = MolDescriptor('Reference Molecule', ref_sdf)
ref_usr = ref_mol.get_usr()

simtool = SimilarityTool()

# connect to database
# retreive all compounds satisfying the input condition
con = pymysql.connect(host='localhost', user='s2192822', passwd='123456', db='s2192822')
cur = con.cursor()
sql = f"SELECT mol.cid, mol.molecule, cp.catn FROM Compounds cp join Molecules mol on cp.id=mol.cid WHERE {cond}"
cur.execute(sql)

# calculate similarity between reference molecule and library compound one by one
result = pd.DataFrame(columns=['Compound', 'Similarity'])
row = cur.fetchone()

while not row is None:
  cid = row[0]
  sdf = row[1]
  cat = row[2]
  mol = MolDescriptor(cid, sdf)
  mol_usr = mol.get_usr()
  sim = simtool.get_similarity_from_usr(ref_usr, mol_usr)
  if sim > sim_cut:
    result.loc[cid, 'Compound'] = f"<a href=moldisplay.php?cid={cid}>{cat}</a>"
    result.loc[cid, 'Similarity'] = sim
  row = cur.fetchone()

# get top 100 most similar compounds
result.sort_values('Similarity', axis=0, ascending=False, inplace=True);
result = result.reset_index()
result.rename(columns={'index':'cid'}, inplace=True)
result.index += 1

# output html source code
print(result.head(100).to_html(escape=False, border=None, table_id="manutable", classes="tableCanSort", justify='left', index=True))
