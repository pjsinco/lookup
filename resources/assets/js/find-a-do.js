var FindADoForm = require('./find-a-do-form.js'),
    Location = require('./location.js');

var FindADo = function(opts) {
    this.form = new FindADoForm(opts);
    this.location = undefined;
};

FindADo.prototype.setLocation = function(loc) {
    this.location = new Location(loc);
    this.form.update(loc);
};

FindADo.prototype.loadLocation = function() {
    var self = this;
    this.location.getRandom(function(location) {
        self.setLocation(location);
    });
};

FindADo.prototype.init = function init() {
    this.loadLocation();
    this.form.init();
};

module.exports = FindADo;
