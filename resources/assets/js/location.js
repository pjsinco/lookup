var madison = require('madison');

var Location = function(loc) {
    
    this.city  = loc.city;
    this.state = loc.state;
    this.zip   = loc.zip;
    this.lat   = loc.lat;
    this.lon   = loc.lon;
    
};

module.exports = Location;
