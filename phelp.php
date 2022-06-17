<?php include "mainheader.php" ?>
<html>
<head>
<?php include_once 'commondep.php' ?>
</head>
<body>
<?php include_once 'menuf.php'; ?>

<div class="div-center">
<style>
  article h3 {
  color: #05386B;
  font-family: Courier New;
  font-weight: heavy;
  }
  
  article h4 {
  font-family: Courier New;
  font-weight: heavy;
  }
  
  article p, article a {
  font-family: Trebuchet MS;
  }
  
  article b {
  color: #F64C72;
  }
</style>

<article>
<header>
<h2> About CompLib </h2>
</header>
<section>
<h3> What is CompLib? </h3>
<p> Complib is a compound database developed in Mar 2022 by B202747. It originated from the website template provided by Dr. Paul Taylor. </p>
<p> Complib is grateful to <a href="#resource">many open resources</a>. Without them, Complib could never be launched. </p> 
<p> Complib provides various tools to explore the library. </p>
<p> 1. Extract statistical information.  </p>
<p> 2. Searching compounds satisfying specific conditions. </p>
<p> 3. Searching most similar compounds by USR fingerprints. </p>
</section>
<section>
<h3> Interested in knowing more? </h3>
<p> Please refer to the <a href='#document'> document</a> at the end for technical information about database. </p>
</section>
<section>
<h3> What is new? </h3>
<p>1. Selected suppliers now show on the top of each page, and now all tools will search <b>only in selected suppliers</b>. </p>
<p>2. <a href="p3.php"> Statistics</a>, <a href="p3a.php">Histogram</a>, and <a href="p4.php">Correlation</a> presentations are greatly changed. </p>
<p>3. <a href="p2smilesex.php"> Search compounds</a>, and <a href="p10a.php"> properties</a> now have validation function to check if the input is valid. </p>
<p>4. <a href="p2smilesex.php"> Search compounds</a> now display maximum and minimum values of each searchable term computed among compounds from selected suppliers. </p>
<p>5. <a href="p10a.php"> Search properties</a> now always retreives top 10,000 compounds instead of showing error when there are more than 10,000 matched compounds. </p>
<p>6. All compound result tables are <b> sortable </b> by columns (just click which column you want to sort). </p>
<p>7. Compound information page includes various information such as <b> lipinski checklist, sdf file download link, and external library links</b>. (e.g. <a href="moldisplay.php?cid=26227">SPH1-012-284</a>)</p>
<p>8. <a href="similar.php"> Similarity Search </a> is a <b> brand new </b> tool! The similarity is based on USR fingerprint and the <a href="pyscript/similar.py"> whole programme including reading sdf file </a> is written by my own. </p>
<p>9. The whole library compound data is now <a href="dbdownload.php">  downloadable</a>.
<p>10. The code is refactorised to minimise redundancy. Common php functions are written in <a href="phpscript/utilfuncs.php"> utilfuncs.php</a>.
<p>11. Brand new layout and <a href="style/mybasic.css"> css files</a>.
<p>12. <a href="index.php"> Index page</a> supports a search bar which can query compound by <b> smiles and catalogue</b>.</p>
</section>
<section>
<h3> Suppliers Selection & Data Download </h3>
<p> The library currently contains 68,116 compounds from five suppliers: Asinex, KeyOrganics, MayBridge, Nanosyn, and Oai40000. </p>
<h4> 1. Select your interested suppliers </h4>
<p> The top banner shows currently selected suppliers in red, unselected suppliers in white. </p>
<p> All suppliers are selected by default, but users can change selection on <a href="p1.php"> Select Suppliers</a>. </p>
<p> Please be aware that supplier selection is a <b> global constraint</b>. All library tools search compounds from your selected suppliers only. </p>
<h4> 2. Download all compounds from suppliers </h4>
<p> The library allows users to <a href="dbdownload.php"> download compounds from specific suppliers </a> for free.</p>
</section>

<section>
<h3> Library Overview & Statistics </h3>
<p> To get an overview of all compounds from selected suppliers, the library provides the following three statistical analysis tools. </p>
<p> 1. Page <a href="p3.php"> Statistics</a> can view maximum, minimum, average, standard deviation of chosen property.</p>
<p> 2. Page <a href="p3a.php">Histogram</a> can view histogram of chosen property.</p>
<p> 3. Page <a href="p4.php">Correlation</a> can view correlation between two chosen properties and their joint plot.</p>
</section>

<section>
<h3> Search compounds </h3>
<p> There are two tools for searching compounds by conditions. </p>
<p> 1. Page <a href="p2smilesex.php"> Search compounds</a> allows users to search compounds within customised range of the number of different atoms. </p>
<p> 2. Page <a href="p10a.php"> Search property</a> allows users to search compounds with exact property. </p>
</section>

<section>
<h3> Similarity Search </h3>
<p> A <a href="similar.php"> similarity tool</a> searches the top 100 most similar compounds to the user uploaded compound. </p>
<p> If you do not have a sdf on hand, please feel free to use <a href="sdffile/rfp.sdf"> this sample</a>. </p>
</section>

<section id="resource">
<h3> Resources used </h3>
<p> There are many resources used either partially or fully in the development of Complib. </p>
<p> The below is a list of resources used, please refer to the comments in the source codes to get more detailed information. </p>
<p> 1. Old website template codes from Dr. Paul Taylor.</p>
<p> 2. Database SDF file and various scripts for extracting and building tables from Dr. Paul Taylor.</p>
<p> 3. jQuery, and tablesorter javascript codes. </p>
<p> 4. Pure css icons from <a href="https://css.gg/app"> css.gg</a>. </p>
<p> 5. Pure css loader from <a href="https://loading.io/css/"> loading.io</a>. </p>
<p> 6. CSS tutorials from <a href="https://www.w3schools.com/css/default.asp"> </a> W3School.</p>
<p> 7. <a href="https://cactus.nci.nih.gov/chemical/structure/"> NCI API</a> for obtaining structure information. </p>
<p> 8. <a href="https://pubchemdocs.ncbi.nlm.nih.gov/pug-rest"> Pubchem API</a> for obtaining Pubchem link to compound. </p>
</section>

<section>
<h3> Miscellaneous </h3>
<p> 1. Php files now have several php command blocks instead of a single block as before. </p>
<p> 2. Many script blocks and style blocks are written <b>inside div object</b> to avoid conflicting with global script/style. </p>
<p> 3. Comments in source code are either in php commentary style or <b> html commentary style</b>. </p>
</section>


<section id="document">
<h3>Technical Notes</h3>
This website is based on a MySQL database consisting of 3 tables which are linked by compound id. Those tables are
<p> 1. the Manufacturer table, which holds the name of each cehmical manufacturer who contributed compounds to the main section. </p>
<p> 2. the Compounds table which holds an entry for each compound. </p>
<p> In addition to the compound name
and a referfence to the manufacturer table for each compound, </p>
<p> it also holds detains about the chemical 
composition and some basic physical properties for each compound.</p>
<p> 3. The Molecules (structure) table. This holds a reference to the compounds table and a copy of the coordinates of each compound.</p>
<p>
The data for these tables was extracted from a master sdf file containing all the entries.</p>
<p>The method of extracting the data and populating the tables was using Java script <b>SDFprop.java</b> and python script <b>updatetabv2.py</b>.
</p>
<p> Compound smiles are obtained by Java script <b>SDF_smiles.java</b> and a python script <b>addsmiles.py</b> for building table Smiles.</p>
<p> Molecule structure table is constructed by python script <b>insertmolsdf.py</b>.</p>
</section>
</article>
</div>

<?php include "footer.php" ?>
</body>
</html>

