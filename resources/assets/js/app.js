//$(document).ready(function() {

//    $('#location').tooltipster({
//        content: $('hiya')
//    });

    var loc = {
        city: '',
        state: '',
        zip: '',
        lat: '',
        lon: ''
    };

    /**
     * 
     *
     */
    function isAZip(query) {
        
    }

    

    /**
     * Ajax in our random location and populate the location form field
     *
     */
    function loadRandomLocation() {
        $('#location').load('api/v1/locations/random', 
            function(responseText, textStatus, jqXHR) {
                var json = JSON.parse(responseText);
                loc.city = json.data.city;
                loc.state = json.data.state;
                loc.zip = json.data.zip;
                loc.lat = json.data.lat;
                loc.lon = json.data.lon;

                $(this).val(json.data.city + ', ' + json.data.state + ' ' + json.data.zip );
            $('.city').val(loc.city);
            $('.state').val(loc.state);
            $('.zip').val(loc.zip);
            $('.lat').val(loc.lat);
            $('.lon').val(loc.lon);
        });
    };


    var locations = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        limit: 10,
        remote: {
            url: 'api/v1/locations/search',
            replace: function(url, query) {
                return url + '?q=' + query;
            },
            filter: function(locations) {
                var userTyped = ($('#location').typeahead('val'));
                return $.map(locations, function(d) {
                        //console.log(d); // gives the array of objects

                        // user typed a city
                        if (isNaN(userTyped)) {
                    
                            // Keep the city, state list unique
                            var unique = _.uniq(d, false, function(item) {
                                //multiple values with _.uniq
                                //http://stackoverflow.com/questions/26306415/underscore-lodash-unique-by-multiple-properties
                                return [item.city, item.state].join();
                            });

                            return $.map(unique, function(e) {
                                return {
                                    city: e.city,
                                    state: e.state,
                                    lat: e.lat,
                                    lon: e.lon,
                                    value: e.city + ', ' + e.state
                                };
                            });
                        } else {
                        // user typed a zip, so let's include zips
                            return $.map(d, function(e) {
                                return {
                                    city: e.city,
                                    state: e.state,
                                    zip: e.zip,
                                    lat: e.lat,
                                    lon: e.lon,
                                    value: e.city + ', ' + e.state + ' ' + e.zip
                                };
                            });
                        }
                    });
            }
        }
    }); 

    locations.initialize();

    $('#location').typeahead({
        hint: true,
        highlight: true,
        minLength: 3,
    }, {
        name: 'locations',
        display: 'value',
        source: locations.ttAdapter(),
        templates: {
            suggestion: function(data) {
                var userTyped = $('#location').typeahead('val');
                if (isNaN(userTyped)) {
                    return '<div>' + data.city + ', ' + data.state + '</div>';
                } else {
                    return '<div>' + data.city + ', ' + data.state + ' ' +
                        data.zip + '</div>';
                }
            }
        },
        engine: Hogan
        
    });

    /**
     * Debug Typeahead events
     *
     */
    $('#location').bind('typeahead:selected', function(evt, suggestion, dataset) {

        console.log('Suggestion: ' + suggestion.value);
        console.log(evt);
        loc.city = suggestion.city;
        loc.state = suggestion.state;
        loc.zip = suggestion.zip;
        loc.lat = suggestion.lat;
        loc.lon = suggestion.lon;

        // TODO 
        // needs work
        $('#location').on('blur', function(evt) {
            updateFormInputsWithLocations();
            console.log($('.city').val());
            console.log($('.state').val());
            console.log($('.zip').val());
        });
        
    });

    function updateFormInputsWithLocations() {
            $('.city').val(loc.city);
            $('.state').val(loc.state);
            $('.zip').val(loc.zip);
            $('.lat').val(loc.lat);
            $('.lon').val(loc.lon);
    }

    function parseLocationField(input) {

    }

    $('#location').on('blur', function(evt) {
        console.log('blurred');
        updateFormInputsWithLocations();
    });


    /**
     * Handle physician/specialty input
     *
     */

    var physicians = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        //limit: 7,
        remote: {
            url: 'api/v1/physicians/search',
            replace: function(url, uriEncodedQuery) {
                return url + '?name=' + uriEncodedQuery + '&city=' + loc.city;
            },
            //wildcard: '%QUERY',
            filter: function(physicians) {
                return $.map(physicians, function(d) {
                    return $.map(d, function(e) {
                        return {
                            first_name: e.first_name,
                            last_name: e.last_name,
                            designation: e.designation,
                            city: e.city,
                            state: e.state,
                            id: e.id,
                            value: e.full_name
                        };
                    });
                });
            }
        }
    });

    var specialties = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        limit: 7,
        remote: {
            url: 'api/v1/specialties/search/%QUERY',

        }

    });

    physicians.initialize();

    $('#what').typeahead({
        hint: false,
        highlight: true,
        minLength: 2,
        //limit: 7,
    }, {
        name: 'physicians',
        //limit: 7,
        display: 'value',
        source: physicians.ttAdapter(),
        templates: {
            header: '<h5 class="typeahead-subhead">Physicians near [city, state]</h5>',
            suggestion: function(data) {
                // TODO
                // remove hard-coded url
                return '<div><a href="http://lookup.dev/physicians/' + data.id + '">' + data.first_name + ' ' + data.last_name + ', ' +
                    data.designation + '; ' + data.city + ', ' + data.state +
                    '</a></div>';
            }
        }
    });

    /**
     * Kickoff
     *
     */
    loadRandomLocation();
    
//});
