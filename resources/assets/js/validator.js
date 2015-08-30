var Location = require('./location.js');

var validator = {

    isZipCode: function(query) {
        return new RegExp(/^\d{5}$/).test(query);
    }

    

};

module.exports = validator;
