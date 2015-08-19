# rCast
rCast is a cross-platform podcast manager. Downloading, playing and managing podcasts can all be done in here. A fork called "rCast lite" is planned. rCast lite's primary purpose is to limit the bandwidth strain on the server it is installed on. It does this by not downloading episodes, and simply serving the files through its own interface.

## Used Libraries
* SimplePIE (RSS feed interaction)
* Bootstrap (Mobile UI)
* AngularJS (For creating the app)
* Angular UI Bootstrap (integration of bootstrap and angular)

## Statuses
0: Episode is not downloaded  
1: Downloading  
2: Downloded, not played  
3: Partially played, bookmark is set  
4: Episode finished  
5: Episode finished, and deleted  
