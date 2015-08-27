var $ = require('jquery'),
    _ = require('underscore'),
    Location = require('./location.js'),
    LocationSearch = require('./location-search.js'),
    PhysSpecialtySearch = require('./phys-specialty-search.js');

var FindADoForm = function(opts) {

    this.defaultSettings = {
        formId: '#findADo',
        locationInput: '#location',
        specialtyInput: '#specialty'
    };

    this.settings = 
        opts ? _.defaults(opts, this.defaultSettings) : this.defaultSettings;

    this.$formId = $(this.settings.formId);
    this.$specialtyInput = $(this.settings.specialtyInput);
    this.$locInput = $(this.settings.locationInput);

    this.$hiddenCity = this.$formId.find('#city');
    this.$hiddenState = this.$formId.find('#state');
    this.$hiddenZip = this.$formId.find('#zip');
    this.$hiddenLat = this.$formId.find('#lat');
    this.$hiddenLon = this.$formId.find('#lon');

    this.locationSearch = new LocationSearch({ input: this.$locInput });
    this.physSpecialtySearch = 
        new PhysSpecialtySearch({ input: this.$specialtyInput });

};

FindADoForm.prototype.init = function() {
    this.locationSearch.init();
    this.physSpecialtySearch.init();
};

FindADoForm.prototype.update = function(loc) {
    this.updateHidden(loc);
    this.locationSearch.update(loc);
};

/**
 * Update hidden input fields in form.
 *
 */
FindADoForm.prototype.updateHidden = function(loc) {
    this.$hiddenCity.val(loc.city);
    this.$hiddenState.val(loc.state);
    this.$hiddenZip.val(loc.zip);
    this.$hiddenLat.val(loc.lat);
    this.$hiddenLon.val(loc.lon);
};

module.exports = FindADoForm;
