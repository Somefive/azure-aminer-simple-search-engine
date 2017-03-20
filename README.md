# azure-aminer-simple-search-engine
A simple search engine of experts in AMiner system.

### Description
This is a simple search engine of experts in AMiner system.
Github account is required to access the search engine. 
By typing a single research field such as *computer graphics* will return the top 10 h-index experts whose interest fields contain the requested field.
Then by clicking the authors listed below, the coauthor panel will be displayed and the top 5 related coauthors will be returned.
Clicking on those coauthors and keeping track of cooperation is available.

### Realization
PHP7 is used to rush this project which takes about 2 days to finish (most time is spent on the front end...).
Redis is also used for caching both experts information and github account information.

### Deployment
The environment of the server is Ubuntu 16.10 LTS plus Apache2 with 1.75G memory and 1G CPU.
As the server locates in westus, the access time is a bit annoying.
The website is [AminerSimpleSearchEngine](http://somefive.westus.cloudapp.azure.com/index.php).

### Data
The search data source is in AMiner system which can be accessed from [AMiner](https://cn.aminer.org/aminernetwork).
The author and coauthor data is used.
Some Python scripts are applied to pre-processing those data.
