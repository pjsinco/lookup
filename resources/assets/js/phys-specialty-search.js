var $ = require('jquery'),
    typeahead = require('./typeahead.0.10.5');

/**
 * Provide autocomplete results for searching on physician/specialty.
 *
 * @param opts.input is a JQuery object.
 */

function PhysSpecialtySearch(opts) {
    this.input = opts.input;
    this.physEngine = {};
    this.specEngine = {};
}

PhysSpecialtySearch.prototype.initBloodhound = function() {


    this.physEngine = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        //limit: 7,
        remote: {
            url: 'api/v1/physicians/search',
            replace: function(url, uriEncodedQuery) {
                // Grab the location from the hidden form fields
                var loc = {
                    city: $('#city').val(),
                    state: $('#state').val(),
                    zip: $('#zip').val(),
                    lat: $('#lat').val(),
                    lon: $('#lon').val()
                };
                var params = $.param(loc);
                return url + '?name=' + uriEncodedQuery + '&' + params;
            },
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
    
    this.specEngine = new Bloodhound({
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

};

PhysSpecialtySearch.prototype.init = function() {

    this.initBloodhound();
    this.physEngine.initialize();
    this.specEngine.initialize();
    this.initTypeahead();
    
};

PhysSpecialtySearch.prototype.update = function(loc) {

    this.location = loc;
    this.physEngine.initialize(true);
    this.specEngine.initialize(true);
    
};

PhysSpecialtySearch.prototype.initTypeahead = function() {

    this.input.typeahead({
        hint: false,
        highlight: true,
        minLength: 2,
        limit: 7,
    }, {
        name: 'specEngine',
        source: this.specEngine.ttAdapter(),
        display: 'value',
        templates: {
            header: '<h5 class="typeahead-subhead">Specialties</h5>',
            suggestion: function(data) {
                // TODO
                // remove hard-coded url
                return '<div><a href="#">' + data.value + "</a></div>";
            }
        }
    }, {
        name: 'physEngine',
        //limit: 7,
        display: 'value',
        source: this.physEngine.ttAdapter(),
        templates: {
            header: '<h5 class="typeahead-subhead">Physicians near [city, state]</h5>',
            suggestion: function(data) {
                // TODO
                // remove hard-coded url
                return '<div><a href="http://lookup.dev/physicians/' + 
                    data.id + '">' + data.first_name + ' ' + data.last_name + ', ' +
                    data.designation + '; ' + data.city + ', ' + data.state +
                    '</a></div>';
            }
        }
    });

};

module.exports = function(opts) {
    return new PhysSpecialtySearch(opts);
}
