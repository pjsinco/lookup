var $ = require('jquery'),
    _ = require('underscore'),
    assert = require('assert'),
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

    this.$form = $(this.settings.formId);
    this.$specialtyInput = $(this.settings.specialtyInput);
    this.$locInput = $(this.settings.locationInput);

    this.$hiddenCity = this.$form.find('#city');
    this.$hiddenState = this.$form.find('#state');
    this.$hiddenZip = this.$form.find('#zip');
    this.$hiddenLat = this.$form.find('#lat');
    this.$hiddenLon = this.$form.find('#lon');

    this.location = new Location({});
    this.locationResolved = false;

    this.locationSearch = new LocationSearch({ input: this.$locInput });
    this.physSpecialtySearch = 
        new PhysSpecialtySearch({ input: this.$specialtyInput });

};


FindADoForm.prototype.bindEvents = function() {
    var self = this;

    this.$locInput.on('typeahead:opened', function(evt) {
        console.log('opened');
        this.locationResolved = false;
    });

    this.$locInput.on('typeahead:closed', function(evt) {
        console.log('closed');

        if (! this.locationResolved) {
            console.info('calling validate');
            self.locationResolved = self.location.validate($(this).val());
        }

    });

    this.$locInput.on('typeahead:cursorchanged', function(evt, suggestion) {
        console.log('cursorchanged');
        console.dir(suggestion);
        console.log($(this).val());
    });

    this.$locInput.on('typeahead:selected', function(evt, suggestion) {
        console.log('selected');
        this.locationResolved = this.location.validate(suggestion)
    });

    this.$locInput.on('typeahead:autocompleted', function(evt, suggestion) {
        console.log('autocompleted');
        console.log($(this).val());
    });

    this.$form.on('submit', function(evt) {
        evt.preventDefault();
        console.log('form submitted');
    });
};

FindADoForm.prototype.validateLocationInput = function() {
    
};

FindADoForm.prototype.validateSpecialtyInput = function() {

};

FindADoForm.prototype.validate = function() {



};

FindADoForm.prototype.init = function() {
    this.locationSearch.init();
    this.physSpecialtySearch.init();
    this.bindEvents();
};

FindADoForm.prototype.update = function(loc) {
    assert.ok(loc, "Assert OK");
    this.location = new Location(loc);
    this.updateHidden(this.location);
    this.locationSearch.update(this.location);
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
