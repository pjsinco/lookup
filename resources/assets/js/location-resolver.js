var LocationResolver = {

    isZipCode: function(query) {
        return new RegExp(/^\d{5}$/).test(query);
    }

    
};

module.exports = LocationResolver;

