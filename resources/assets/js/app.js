var FindADo = (function() {

    function init() {

        var $locInput = $('#location');
        var $physInput = $('#physician');
        var locationResolved = false;

        var location = {
            city: '',
            state: '',
            zip: '',
            lat: '',
            lon: ''
        };

        /**
         * Ajax in our random location and populate the location input
         *
         */
        function loadRandomLocation() {
            $locInput.load('api/v1/locations/random', 
                function(responseText, textStatus, jqXHR) {
                    var json = JSON.parse(responseText);
                    location.city = json.data.city;
                    location.state = json.data.state;
                    location.zip = json.data.zip;
                    location.lat = json.data.lat;
                    location.lon = json.data.lon;

                    $(this).val(json.data.city + ', ' + json.data.state + ' ' + json.data.zip );
                $('.city').val(location.city);
                $('.state').val(location.state);
                $('.zip').val(location.zip);
                $('.lat').val(location.lat);
                $('.lon').val(location.lon);
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
                    var userTyped = ($locInput.typeahead('val'));
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

        $locInput.typeahead({
            hint: true,
            highlight: true,
            minLength: 3,
        }, {
            name: 'locations',
            display: 'value',
            source: locations.ttAdapter(),
            templates: {
                suggestion: function(data) {
                    var userTyped = $locInput.typeahead('val');
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
        $locInput.bind('typeahead:selected', function(evt, suggestion, dataset) {
            console.log('suggestion.value: ' + suggestion.value);
            console.log('suggestion.city: ' + suggestion.city);
            console.log('suggestion.state: ' + suggestion.state);
            console.log('suggestion.lat: ' + suggestion.lat);
            console.log('suggestion.lon: ' + suggestion.lon);
            console.log(evt);
            location.city = suggestion.city;
            location.state = suggestion.state;
            location.zip = suggestion.zip;
            location.lat = suggestion.lat;
            location.lon = suggestion.lon;

            // TODO 
            // needs work
            $locInput.on('blur', function(evt) {
                updateFormInputsWithLocations();
                console.log($('.city').val());
                console.log($('.state').val());
                console.log($('.zip').val());
            });
        });

        $locInput.bind('typeahead:autocompleted', function(evt, suggestion) {
            updateLocation(suggestion);
            updateFormInputsWithLocations();
            console.log('Autocompleted');
            console.log('Suggestion.value: ' + suggestion.city);
            locations.get(suggestion.city, function(d) {
                console.log(d);
            })
        });

        function updateLocation(suggestion) {
            location.city = suggestion.city;
            location.state = suggestion.state;
            location.zip = suggestion.zip;
            location.lat = suggestion.lat;
            location.lon = suggestion.lon;
        }

        function updateFormInputsWithLocations() {
                $('.city').val(location.city);
                $('.state').val(location.state);
                $('.zip').val(location.zip);
                $('.lat').val(location.lat);
                $('.lon').val(location.lon);
        }
        
        /**
         * Determines whether a string is a zip code.
         *
         */
        function isZipCode(query) {
            // TODO
            // do we want to handle 9-digit zips?
            //var pattern = new RegExp(/^\d{5}$|^\d{5}-\d{4}$/)
            var pattern = new RegExp(/^\d{5}$/);
            return pattern.test(query);
        }

        function loadZip(zip) {
            $.get(
                
            );
        }

        /**
         * Try to find a match for an un-autocompleted location input
         *
         */
        function resolveLocation(query) {
            console.info('resolving location');
            if (isZipCode(query.trim())) {
                console.log('is zip code');
                loadZip(query.trim());
            }

            var x = []
            locations.get(query, function(suggestions) {
                // TODO
                // overly simplistic for now 
                // -- doesn't care about the state, 
                //    just grabs the first matching city
                suggestions.forEach(function(suggestion) {
                    if (suggestion.city.toLowerCase() == query.toLowerCase()) {
                        $locInput.typeahead('val', suggestion.value);
                        updateLocation(suggestion);
                        updateFormInputsWithLocations();
                    }
                });
            });
        }

        function parseLocationField(input) {
            
        }

        $locInput.on('blur', function(evt) {
            console.log('blurred');
            console.log('value: ' + evt.currentTarget.value);
            resolveLocation(evt.currentTarget.value);
            //updateFormInputsWithLocations();
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
                    return url + '?name=' + uriEncodedQuery + '&city=' + location.city;
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
            prefetch: {
                url: 'http://lookup.dev/data/specialties.json',
                filter: function(specialties) {
                    console.log(specialties);
                    return $.map(specialties, function(d) {
                        return $.map(d, function(e) {
                            return {
                                code: e.code,
                                value: e.name
                            };
                        });
                    });
                }
            }
        });

        physicians.initialize();
        specialties.initialize();

        $physInput.typeahead({
            hint: false,
            highlight: true,
            minLength: 2,
            limit: 7,
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
        }, {
            name: 'specialties',
            source: specialties.ttAdapter(),
            display: 'value',
            templates: {
                header: '<h5 class="typeahead-subhead">Specialties</h5>',
                suggestion: function(data) {
                    // TODO
                    // remove hard-coded url
                    return '<div><a href="#">' + data.value + "</a></div>";
                }
            }
        });

        /**
         * Kickoff
         *
         */
        loadRandomLocation();

    };

    return {
        init: init
    };

}());

//    $('#location').tooltipster({
//        content: $('hiya')
//    });
