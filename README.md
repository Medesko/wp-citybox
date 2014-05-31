WP Citybox plugin
============
##Goals
a) allows to add a city name to a post

b) lets posts be searchable using WordPress search by city

c) should display by adding a shortcut code


## How use  
Unzip the plugin files.

Connect to your site’s server using FTP.

Navigate to the /wp-content/plugins directory.

Upload the plugin folder to the /wp-content/plugins directory on your web server.

Go to the Dashboard’s Plugins page and you see the new plugin listed and click to active.

add Shortcodes in post, page, widget from Dashboard   
```
[city]
```
To insert in template file  
```
$city = get_post_meta($post->ID, '_city_box', true);
	echo $city; 
```
<a href="http://onepassionate.com/">Demo</a> 


