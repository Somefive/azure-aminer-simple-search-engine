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

## Some detailed information associated with assignment requirement. TA may use it.

### Some tricks with pretty url
Using .htaccess, /expert_finding & /coauthors are rewrited into /expert_finding.php & /coauthors.php.
The api interfaces /expert_finding?domain={domain} & /coauthors?id={id} are provided with only expert id and their h-index/cooperation counter sorted by h-index/cooperation counter. No detailed information is returned.
Compared to that, the web front use another api interface getdata.php?type={interest/coauthors} which only return 5/10 items of the list above but with detailed information of each experts. This api is designed for the use of the front web while the other two are used for other application to reuse them.

### The redis data format
After pre-processing data, I save those data in the format of json string in redis. The pre-processing part already sort the coauthors and interest with cooperation counter or author h-index. So when the front web requests data, the backend service only need to fetch the raw json string from the redis database and decode it into list or object. No need for further sort.
In redis, there are three types of data. There keys are formatted as aminer::coauthers::$id, aminer::interest::$field, aminer::author::$id. They represent the coauthors list of one expert with specific id, the author list of a specific interest and the detailed data of one author with specific id.

### The github auth part
By redirecting the url to the authorization page of github, the user email is required. Then github will send the code of user to backend service and backend service will request access_token with the given code. After that, use the access_token to exchange the user emain address. After getting user's email address, redis will record (access_token, email) tuple which will later return the token to the front web and save it in cookie as the token of user.
