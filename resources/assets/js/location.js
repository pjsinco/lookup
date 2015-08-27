var $ = require('jquery'),
    madison = require('madison');


var Location = function(loc) {
    
    this.city  = loc.city  || '';
    this.state = loc.state || '';
    this.zip   = loc.zip   || '';
    this.lat   = loc.lat   || '';
    this.lon   = loc.lon   || '';

};

Location.prototype.update = function(loc) {
    //this.setLocation(loc);
};

Location.prototype.validate = function(query) {
    return this.resolve(query);
}

Location.prototype.getByZip = function(zip, callback) {

    $.get('api/v1/locations/zip?' + $.param({q: zip}),
        function(responseText) {
            callback(responseText['data'][0]);
        }
    );
};

Location.prototype.isZipCode = function(query) {
    return new RegExp(/^\d{5}$/).test(query);
};

Location.prototype.resolve = function(query) {

    if (this.isZipCode(query.trim())) {
        this.getByZip(query.trim(), this.setLocation);
    }

};

Location.prototype.setLocation = function(loc) {

    this.city = loc.city;
    this.state = loc.state;
    this.zip = loc.zip;
    this.lat = loc.lat;
    this.lon = loc.lon;

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
