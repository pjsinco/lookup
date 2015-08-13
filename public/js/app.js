//$(document).ready(function() {


    var loc = {
        city: '',
        state: '',
        zip: '',
        lat: '',
        lon: ''
    };

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
            url: 'api/v1/locations/%QUERY',
            wildcard: '%QUERY',
            filter: function(locations) {
                return $.map(locations, function(d) {
                    return {
                        city: d.city,
                        state: d.state,
                        zip: d.zip,
                        lat: d.lat,
                        lon: d.lon,
                        value: d.city + ', ' + d.state + ' ' + d.zip
                    }
                })
            }
        }
    }); 

    locations.initialize();

    $('#location').typeahead({
        hint: false,
        highlight: true,
        minLength: 3,
    }, {
        name: 'locations',
        limit: 7,
        display: 'value',
        source: locations.ttAdapter(),
        templates: {
            suggestion: function(data) {
                return '<div>' + data.city + ', ' + data.state + ' ' +
                    data.zip + '</div>';
            }
            //empty: [
                //'<div class="empty-message">',
                //'Sorry, unable to find any matches',
                //'</div>',
            //].join('\n')
        }
        
    });

    /**
     * Debug Typeahead events
     *
     */
    $('#location').bind('typeahead:select', function(evt, suggestion) {
        console.log(suggestion);
        loc.city = suggestion.city;
        loc.state = suggestion.state;
        loc.zip = suggestion.zip;
        loc.lat = suggestion.lat;
        loc.lon = suggestion.lon;
    });

      $('#location').bind('typeahead:idle', function(evt) {
          console.log('idle');
          console.log(evt);
      });

    $('#location').bind('typeahead:asyncrequest', function(evt, query, dataset) {
        console.log('asyncrequest');
        /*console.log(evt);*/
        /*console.log(query);*/
        /*console.log(dataset);*/
    });


    $('#location').on('blur', function(evt) {
        $('.city').val(loc.city);
        $('.state').val(loc.state);
        $('.zip').val(loc.zip);
        $('.lat').val(loc.lat);
        $('.lon').val(loc.lon);
    });


    /**
     * Handle physician/specialty input
     *
     */

    var physicians = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        limit: 10,
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

    physicians.initialize();

    $('#what').typeahead({
        hint: false,
        highlight: true,
        minLength: 3,
    }, {
        name: 'physicians',
        limit: 7,
        display: 'value',
        source: physicians.ttAdapter(),
        templates: {
            header: '<h5 class="typeahead-subhead">Physicians near [city, state]</h5>',
            suggestion: function(data) {
                return '<div>' + data.first_name + ' ' + data.last_name + ', ' +
                    data.designation + '; ' + data.city + ', ' + data.state +
                    '</div>';
            }
            //empty: [
                //'<div class="empty-message">',
                //'Sorry, unable to find any matches',
                //'</div>',
            //].join('\n')
        }
    });

    /**
     * Kickoff
     *
     */
    loadRandomLocation();
    
//});

//# sourceMappingURL=app.js.map