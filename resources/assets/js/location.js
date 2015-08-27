var $ = require('jquery'),
    madison = require('madison');


var Location = function(loc) {
    
    this.city  = loc.city  || '';
    this.state = loc.state || '';
    this.zip   = loc.zip   || '';
    this.lat   = loc.lat   || '';
    this.lon   = loc.lon   || '';

    this.loc = {
        city: this.city,
        state: this.state,
        zip: this.zip,
        lat: this.lat,
        lon: this.lon,
    };
    
};


Location.prototype.setLocation = function() {

    

};

Location.prototype.getRandom = function(callback) {
    $.get(
        '/api/v1/locations/random', 
        function(responseText) {
            callback(responseText.data);
        }
    );
};

module.exports = Location;
