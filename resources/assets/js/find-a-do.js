var FindADoForm = require('./find-a-do-form.js'),
    Location = require('./location.js');

var FindADo = function(opts) {
    this.form = new FindADoForm(opts);
    this.form.init();
    this.location = {};
};

FindADo.prototype.setLocation = function(loc) {
    this.location = loc;
};

FindADo.prototype.init = function init() {
    this.location = this.form.loadLocation();
};

module.exports = FindADo;
