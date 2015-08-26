var $ = require('jquery'),
    _ = require('underscore')
    Location = require('./location.js'),
    locSearch = require('./location-typeahead.js');

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

    this.locationSearch = new locSearch({ input: this.$locInput });
};

FindADoForm.prototype.init = function() {
    this.locationSearch.init();
};

FindADoForm.prototype.loadLocation = function() {
    var self = this;
    this.getRandomLocation(function(data) {
        self.setLocation(data);
        self.updateLocationField(data);
        self.updateHidden(data);
        self.locationSearch.update(data);
    });
};

FindADoForm.prototype.getRandomLocation = function(callback) {
    $.get(
        '/api/v1/locations/random', 
        function(responseText) {
            callback(responseText.data);
        }
    );
};

FindADoForm.prototype.setLocation = function(loc) {
    console.info('inside setLocation');
    this.location = new Location(loc);
};

FindADoForm.prototype.updateLocationField = function(loc) {
    console.info('inside updateLocationField');
    this.locationSearch.hiya();
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
