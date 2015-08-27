var $ = require('jquery'),
    typeahead = require('./typeahead.0.10.5');

/**
 * @param opts.input is a jQ object.
 *
 */
function LocationSearch(opts) {
    this.input = opts.input;
    this.engine = {};
}

LocationSearch.prototype.initBloodhound = function() {
    var locationInput = this.input;
    this.engine = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        limit: 10,
        remote: {
            url: 'api/v1/locations/search',
            replace: function(url, query) {
                return url + '?q=' + query;
            },
            filter: function(locations) {
                var userTyped = locationInput.typeahead('val');
                return $.map(locations, function(d) {
                    // user typed a city
                    if (isNaN(userTyped)) {
                        
                        // Keep the city, state list unique
                        var unique = _.uniq(d, false, function(item) {
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
};

LocationSearch.prototype.hiya = function() {
    console.log('hiya');
};

LocationSearch.prototype.update = function(loc) {
    this.input.typeahead('val', loc.city + ', ' +
        loc.state + ' ' + loc.zip);
};

LocationSearch.prototype.init = function() {
    this.initBloodhound();
    this.engine.initialize();
    var locationInput = this.input;

    this.input.typeahead({
        hint: true,
        highlight: true,
        minLength: 3,
    }, {
        name: 'engine',
        display: 'value',
        source: this.engine.ttAdapter(),
        templates: {
            suggestion: function(data) {
                var userTyped = locationInput.typeahead('val');
                if (isNaN(userTyped)) {
                    return '<div>' + data.city + ', ' + data.state + '</div>';
                } else {
                    return '<div>' + data.city + ', ' + data.state + ' ' +
                data.zip + '</div>';
                }
            },
            engine: Hogan
        }
    });
};

module.exports = function(opts) {
    return new LocationSearch(opts);
}
