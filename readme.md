#TODO    

* Resolve the problem with CORS headers. Right now, we've opened up the app. See top of /public/index.php.

* Make both "St. Louis" and "Saint Louis" and "St Louis" available
    * As well as other "Saint" cities
    * "Ft Worth" vs. "Fort Worth"
* Note mismatch between "St" and "Saint" in locations table, physicians table

* What to do with docs with no street address?

```
physicians table
------------------+----------------+
| city             | state_province |
+------------------+----------------+
| Saint Louis      | MO             |
| Saint Louis      | MI             |
| Lake St Louis    | MO             |
| Lake Saint Louis | MO             |
| Bay Saint Louis  | MS             |
+------------------+----------------+

locations table
+---------------+-------+
| city          | state |
+---------------+-------+
| Bay St Louis  | MS    |
| St Louis      | MI    |
| East St Louis | IL    |
| St Louis      | MO    |
| Lake St Louis | MO    |
+---------------+-------+
```

* Some physicians have empty fields for city
* In the FindADO query, can we not concatenate fellow designations onto the `designation` field?
        * See Dr. Hubka: "DO, FACOOG, FACOG,CS"

#dboFindaDO
* Fri Jul 31 11:50:51 2015 CDT 
    * keeping field names the same but for one:
        * change id to aoa_mem_id
    * include gender?
    * img src?
    * can blanks be blank instead of "BLANK"

#ZIP codes
* Blog: [Getting All ZIP Codes In A Given Radius From A Known Point / ZIP Code Via PHP And MySQL](https://www.dougv.com/2009/03/getting-all-zip-codes-in-a-given-radius-from-a-known-point-zip-code-via-php-and-mysql/)

#Geolocation
* Service: [MaxMind](https://www.maxmind.com/en/home)
* Service: [Geocodio](http://geocod.io/)
    * for looking up longitude, latitude based on address

#Autocomplete
* jsfiddle: [example in vanilla js](http://jsfiddle.net/e6220t92/2/)
    * Stack: [Implementing a AutoComplete dropdown in plain JavaScript](http://stackoverflow.com/questions/28842355/implementing-a-autocomplete-dropdown-in-plain-javascript)

* Library: [Awesomplete](http://leaverou.github.io/awesomplete/)
    * [Github](https://github.com/LeaVerou/awesomplete)

* Library: [Typeahead](https://github.com/twitter/typeahead.js/)

#Mapping
* Library: [Mapbox](https://www.mapbox.com/)
* Library: [Leaflet](http://leafletjs.com/)
    * [Github](https://github.com/Leaflet/Leaflet/)

#####Thu May  7 16:50:40 2015 CDT
* [Why I Decided To Embrace Laravel](http://www.toptal.com/laravel/why-i-decided-to-embrace-laravel)

#####Fri May 22 08:41:08 2015 CDT
* Codrops: [Inspiration for Text Styles and Hover Effects](http://tympanus.net/codrops/2015/05/13/inspiration-for-text-styles-and-hover-effects/)

#####Wed Jun 10 06:42:30 2015 CDT
* ["Build Unique Search Experiences"](http://www.algolia.com/)
    * "Hosted Search API that delivers instant and relevant results from the first keystroke"

#####Fri Jun 12 10:33:47 2015 CDT
    
#####Github: [JSON Server](https://github.com/typicode/json-server)
"Get a full fake REST API with zero coding in less than 30 seconds (seriously)"

#####Thu Jul  2 09:30:51 2015 EDT
#####Checkbox Trickery with CSS
* [Blog post](http://codersblock.com/blog/checkbox-trickery-with-css/?utm_source=html5weekly&utm_medium=email)

#####Wed Jul 22 08:27:34 2015 CDT
* Handpicked code snippets you can use in your web projects. Find web design inspiration with code samples: [codemyui](http://codemyui.com/)

#####Thu Jul 30 16:09:49 2015 CDT
* [Zip Code DB](http://www.unitedstateszipcodes.org/zip-code-database/)
    * ~~using this one to start~~
    * lat, lon not precise
* [http://federalgovernmentzipcodes.us/](http://federalgovernmentzipcodes.us/)
* [Americas Open Geocode (AOG) database](http://www.opengeocode.org/download.php#cityzip)

* Component design inspiration: [Call to Idea](http://www.calltoidea.com/index.php)

#####Fri Jul 31 10:07:57 2015 CDT
* Blog: [Calculate Distance by Latitude and Longitude using PHP](http://www.geodatasource.com/developers/php)
* Blog: [Calculating distances from latitude and longitude (PHP)](http://sniptools.com/latitudeLongitude.php)
* Blog: [Distance Formula in PHP – Calculate distance between two points](http://www.techrecite.com/distance-formula-in-php-calculate-distance-between-two-points-programmatically/)

#####Mon Aug  3 06:24:31 2015 CDT
* Blog: [Learn to send emails using Gmail and Sendgrid in Laravel 5](http://learninglaravel.net/learn-to-send-emails-using-gmail-and-sendgrid-in-laravel-5)

#####Tue Aug  4 12:46:07 2015 CDT
* WPBeginner: [How to Display a User’s IP Address in WordPress](http://www.wpbeginner.com/wp-tutorials/how-to-display-a-users-ip-address-in-wordpress/)
* SitePoint: [Building a JavaScript Autocomplete Widget with Awesomplete](http://www.sitepoint.com/javascript-autocomplete-widget-awesomplete/)
* StackOverflow: [Why does npm install say I have unmet dependencies?](http://stackoverflow.com/questions/20764881/why-does-npm-install-say-i-have-unmet-dependencies)

* How we brought in Typeahead using bower:
        * IMPORTANT: We do this in our host shell, not in the Homestead VM

    * install bower locally
    ```
    sudo npm install bower --save-dev
    ```

    * set up .bowerrc; in it, say way to put our bower files
    ```
    {
        "directory": "vendor/bower_dl"
    }
    ```

    * make a bower.json file
    ```
    bower init
    ```

    * install typeahead.js
    ```
    bower install typeahead.js --save
    ```

    * make a gulp task to copy from bower_dl to public/js
    ```
    gulp.task('copyfiles', function() {
    
        gulp.src('vendor/bower_dl/typeahead.js/dist/typeahead.bundle.js' )
            .pipe(gulp.dest('public/js'));
    
    });
    ```

* Blog: [Typeahead.js Autocomplete Suggestion + Bloodhount Remote Data - Tutorial and Demo](http://mycodde.blogspot.com/2014/12/typeaheadjs-autocomplete-suggestion.html)

* StackOverflow: [Working twitter-typeahead example?](http://stackoverflow.com/questions/28102146/working-twitter-typeahead-example)

* Today, we brought in some basic typeahead styles from here: StackOverflow [Twitter's typeahead.js suggestions are not styled (have no border, transparent background, etc.)](http://stackoverflow.com/questions/20198247/twitters-typeahead-js-suggestions-are-not-styled-have-no-border-transparent-b)

* Blog: [Simple Typeahead With AngularJS and Laravel – Part 1](http://ryanchenkie.com/typeahead-part-1/)
* Blog: [Simple Typeahead With AngularJS and Laravel – Part 2](http://ryanchenkie.com/typeahead-part-2/)

#####Wed Aug  5 14:56:37 2015 CDT
* StackOverflow: [typeahead, bloodhound : remote works but not prefetch](http://stackoverflow.com/questions/23569375/typeahead-bloodhound-remote-works-but-not-prefetch)

#####Thu Aug  6 08:03:25 2015 CDT
* Company Blog: [Full Text Search in your Database: Algolia vs Elasticsearch](https://blog.algolia.com/full-text-search-in-your-database-algolia-versus-elasticsearch/)

#####Thu Aug  6 17:07:23 2015 CDT
* jQ Plugin: [Nice form styling](http://brianreavis.github.io/selectize.js/)

#####Fri Aug  7 06:15:36 2015 CDT
* Find a for JS library on a CDN: [jsDelivr](http://www.jsdelivr.com)

######Sat Aug  8 11:11:29 2015 CDT
* Autocomplete, search: [SwiftType](https://swiftype.com/)

######Wed Aug 12 17:06:18 2015 CDT
* How to add additional query string parameters to the URL request
        * Typeahead issues: [Additional Querystring Parameters](https://github.com/twitter/typeahead.js/issues/377)

* StackOverflow: [Laravel Route basic: Get a route with old fashioned php params](http://stackoverflow.com/questions/25621556/laravel-route-basic-get-a-route-with-old-fashioned-php-params)

* StackOverflow: [RESTful URL design for search](http://stackoverflow.com/questions/207477/restful-url-design-for-search)

* StackOverflow: [Typeahead.js - Search in multiple property values](http://stackoverflow.com/questions/21850707/typeahead-js-search-in-multiple-property-values)

#####Fri Aug 14 05:48:57 2015 CDT
* StackOverflow: [How to use typeahead.js with a large database](http://stackoverflow.com/questions/18084408/how-to-use-typeahead-js-with-a-large-database)

* StackOverflow: [Calculating distance between zip codes in PHP](http://stackoverflow.com/questions/407989/calculating-distance-between-zip-codes-in-php)

* Blog: [Why empty states deserve more design time](http://blog.invisionapp.com/why-empty-states-deserve-more-design-time/)
    * Possible empty state:
        "We didn't find any osteopathic physicians in your area. But here's a list of DOs in your state:"

#####Mon Aug 17 09:33:25 2015 CDT
* Typeahead issue: [Dropdown closes and reopens on keypress with remote dataset #176](https://github.com/twitter/typeahead.js/issues/176)

* [Tooltipster](http://iamceege.github.io/tooltipster/)

* Typeahead 0.10.5
    * [Bloodhound docs](https://github.com/twitter/typeahead.js/blob/f835e162ec4551479114fd245c907ae032de692a/doc/bloodhound.md)
    * [Typeahead docs](https://github.com/twitter/typeahead.js/blob/a9ac72afffdc88496700f7b26342b77b191f9a0a/doc/jquery_typeahead.md)

######Wed Aug 19 07:04:35 2015 CDT
* We can prefetch the specialty data. Yes?
* Blog: [Miles to Latitude and Miles to Longitude Converter](http://snipplr.com/view/67019/miles-to-latitude-and-miles-to-longitude-converter/)
* Google Developers: [Creating a Store Locator with PHP, MySQL & Google Maps](https://developers.google.com/maps/articles/phpsqlsearch_v3)
* Drawing a radius around a point we use the Haversine formula

#####Thu Aug 20 09:00:11 2015 CDT
* StackOverflow: [JS module pattern](http://stackoverflow.com/questions/27801062/is-it-possible-to-wrap-a-single-jquery-document-ready-event-around-a-scriptbundl):  
    "If you're looking at the JavaScript files that you wrote (not jquery-{version}.js), then create a method that will be publicly accessible from outside your files that you call on the page."

    ```js
    var Application = (function () {
        // All your code

        function init() {
            // All your code that you have inside $(document).ready()
        }

        return {
            init: init
        };
    }());
    ```
    "Do the above for all of your files, then on any page that needs it, or in your _Layout page if it's going to run on every page have the following at the bottom"
    ```html
    <script type="text/javascript">
        $(document).ready(function () {
            Application.init();
            // etc for the other files.
        });
    </script>

    ```

* StackOverflow: [ZIP Code (US Postal Code) validation](http://stackoverflow.com/questions/160550/zip-code-us-postal-code-validation)

* StackOverflow: [Convert laravel object to array](http://stackoverflow.com/questions/26174267/convert-laravel-object-to-array)
    * Kind of a hack:
    ```php
    DB::setFetchMode(PDO::FETCH_ASSOC);
    
    // then
    DB::table(..)->get(); // array of arrays instead of objects
    
    // of course to revert the fetch mode you need to set it again
    DB::setFetchMode(PDO::FETCH_CLASS);
    ```

* sqlsrv/mssql: Connecting to Imis
    * Blog: [Connecting PHP to Microsoft SQL Server on Linux](https://davejamesmiller.com/blog/connecting-php-to-microsoft-sql-server-on-linux)
        * Hard to say, but I think this one helped the most
        * Note configuration in /etc/freetds/freetds.conf
            ```
            [imis]
                 host = sql05-1.aoanet.local
                 port = 1433
                 tds version = 8.0
            ```
        * Command to test connection:
            ```
            tsql -H imis -p 1433 -U psinco_ro -P Read5Only -D imis
            ```
    * Laracasts forum: [sqlsrv driver on Linux?](https://laracasts.com/discuss/channels/general-discussion/sqlsrv-driver-on-linux)
    * Disable SELinux:
    ```
    vagrant@homestead:~$ setsebool -P httpd_can_network_connect_db 1
    setsebool:  SELinux is disabled.
    ```
    * Laracasts forum: [Connect Laravel to Microsoft SQL](https://laracasts.com/discuss/channels/general-discussion/connect-laravel-to-microsoft-sql)

#####Tue Aug 25 12:53:25 2015 CDT
* Sort of a recipe for browerserify + gulp:
    * Github: [Browserify + Uglify2 with sourcemaps](https://github.com/gulpjs/gulp/blob/master/docs/recipes/browserify-uglify-sourcemap.md)

#####Wed Aug 26 10:27:47 2015 CDT
* StackOverflow: [jquery set ajax result to an outside variable callback](http://stackoverflow.com/questions/6344683/jquery-set-ajax-result-to-an-outside-variable-callback)
* StackOverflow: [Javascript “this” pointer within nested function](http://stackoverflow.com/questions/9644044/javascript-this-pointer-within-nested-function)

* multiple values with Underscore's uniq():
    * StackOverflow: [underscore/lodash unique by multiple properties](http://stackoverflow.com/questions/26306415/underscore-lodash-unique-by-multiple-properties)

* CSS-Tricks: [How Do You Structure JavaScript? The Module Pattern Edition](https://css-tricks.com/how-do-you-structure-javascript-the-module-pattern-edition/)

#####Thu Aug 27 04:55:24 2015 CDT
* Blog: [Setting Up A JS App With Gulp and Browserify](https://quickleft.com/blog/setting-up-a-clientside-javascript-project-with-gulp-and-browserify/)
* Blog: [Writing Testable Frontend Javascript Part 1 - Anti-patterns and their fixes](https://shanetomlinson.com/2013/testing-javascript-frontend-part-1-anti-patterns-and-fixes/)
* Blog: [Writing Testable Frontend Javascript Part 2 - Refactor away anti-patterns](https://shanetomlinson.com/2013/writing-testable-javascript-part-2-refactor-away-anti-patterns/)
* AListApart: [The Design of Code: Organizing JavaScript](http://alistapart.com/article/the-design-of-code-organizing-javascript)
* Google: [Debugging JavaScript in Chrome DevTools](https://developer.chrome.com/devtools/docs/javascript-debugging)

#####Fri Aug 28 16:24:31 2015 CDT
* Slides: [BACKBONE.JS FOR WORDPRESS DEVELOPERS](http://kadamwhite.github.io/talks/2014/backbone-wordpress-wpsessions/#/)
* Blog: [Backbone.js Tutorial: Getting Started with Backbone](http://www.korenlc.com/backbone-js-tutorial-getting-started-with-backbone/)
* Sitepoint: [Backbone.js Basics: Models, Views, Collections and Templates](http://www.sitepoint.com/backbone-basics-models-views-collections-templates/)
* CodeProject: [BackBone Tutorial – Part 1: Introduction to Backbone.Js](http://www.codeproject.com/Articles/795965/BackBone-Tutorial-Part-Introduction-to-Backbone-Js)
* Spring.io: [Consuming a RESTful Web Service with Backbone.js](https://spring.io/guides/gs/consuming-rest-backbone/)
* Wordpress.tv [K Adam White: Evolving Your JavaScript with Backbone.js](http://wordpress.tv/2013/09/05/k-adam-white-evolving-your-javascript-with-backbone-js/)
        * Github [backbone-wordpress-demo](https://github.com/kadamwhite/backbone-wordpress-demo)
* TutsPlus: [Using Backbone Within the WordPress Admin: The Back End](http://code.tutsplus.com/articles/using-backbone-within-the-wordpress-admin-the-back-end--wp-30056)
* TutsPlus: [Using Backbone Within the WordPress Admin: The Front End](http://code.tutsplus.com/tutorials/using-backbone-within-the-wordpress-admin-the-front-end--wp-30121)
* [Backbone questions on wordpress.stackexchange.com](http://wordpress.stackexchange.com/search?q=backbone)
* Chrome Extension: [Backbone-Debugger](https://github.com/Maluen/Backbone-Debugger)
* Smashing: [WordPress Essentials: How To Create A WordPress Plugin (2011)](http://www.smashingmagazine.com/2011/09/how-to-create-a-wordpress-plugin/)

#####Sun Aug 30 07:28:54 2015 CDT
* Sitepoint: [Getting Started with Browserify](http://www.sitepoint.com/getting-started-browserify/)

#####Mon Aug 31 18:36:18 2015 CDT
* Github/Packagist: [CORS in Laravel 5](https://github.com/barryvdh/laravel-cors)

#####Thu Sep  3 09:35:41 2015 CDT
* Github/Packagist: [ApiGuard](https://github.com/chrisbjr/api-guard)
> A simple way of authenticating your APIs with API keys using Laravel. 


######Tue Sep 15 06:49:46 2015 CDT
* TutsPlus: [Laravel 4: A Start at a RESTful API (Updated)](http://code.tutsplus.com/tutorials/laravel-4-a-start-at-a-restful-api-updated--net-29785)

######Fri Sep 18 19:07:11 2015 CDT
* Changes to locations-2015-08-26.csv:
    * Remove 20252--Washington, D.C.
        * That's Smokey the Bear's ZIP code
    * Add a minus sign to the longitude of one of the ZIPs for Lincoln, NE
        * That's an error in the original database file

######Sat Sep 19 08:09:56 2015 CDT
* [Calculate distance between 2 coordinate](http://www.geodatasource.com/developers/php)
