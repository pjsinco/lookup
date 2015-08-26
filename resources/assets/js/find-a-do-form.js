var $ = require('jquery'),
    Location = require('./location'),
    //typeahead = require('typeahead.js');
    typeahead = require('./typeahead.0.10.5');


var FindADoForm = function(formId, specialtyInput, locationInput) {

    this.$formId = $(formId);
    this.$specialtyInput = $(specialtyInput);
    this.$locInput = $(locationInput);
    this.$hiddenCity = this.$formId.find('#city');
    this.$hiddenState = this.$formId.find('#state');
    this.$hiddenZip = this.$formId.find('#zip');
    this.$hiddenLat = this.$formId.find('#lat');
    this.$hiddenLon = this.$formId.find('#lon');
    this.location = {};

};
                
                //$(this).typeahead('val', json.data.city + ', ' + 
                //    json.data.state + ' ' + json.data.zip );

                //updateFormLocationInputs(location);

// http://stackoverflow.com/questions/6344683/
//    jquery-set-ajax-result-to-an-outside-variable-callback
// http://stackoverflow.com/questions/9644044/
//    javascript-this-pointer-within-nested-function
FindADoForm.prototype.loadLocation = function() {
    var self = this;
    this.getRandomLocation(function(data) {
        self.setLocation(data);
        self.updateLocationField(data);
        self.updateHiddenLocationFields(data);
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
    this.$locInput = loc;
};

FindADoForm.prototype.updateHiddenLocationFields = function(loc) {
    this.$hiddenCity.val(loc.city);
    this.$hiddenState.val(loc.state);
    this.$hiddenZip.val(loc.zip);
    this.$hiddenLat.val(loc.lat);
    this.$hiddenLon.val(loc.lon);
};

module.exports = FindADoForm;
