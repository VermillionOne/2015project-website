/*jslint unparam: true*/
/*global app,window*/
app.provider('StorageProvider', function () {

  // Invoke strict mode
  "use strict";

  // Variable declarations
  var suarayDataDefaults;

  // Make sure these properties always exist in case the structure breaks
  // Data defaults when no stored data is found
  suarayDataDefaults = {};

  /**
   * Retrieve property via dot notation string, otherwise return null
   *
   * @param  {object} object             The object that is to be searched
   * @param  {string} dotNotationSearch  A string representation of dot notation, example: 'event.id'
   * @return {mixed}                     The value found or null if not found
   */
  function getProperty(object, dotNotationSearch) {

    // Variable declarations
    var searchKeys;

    // Get keys to search with
    searchKeys = dotNotationSearch.split(".");

    // If we have keys to search for and we have a valid object
    while (searchKeys.length && object) {

      // Reset the object to its descendant
      object = object[searchKeys.shift()];
    }

    // If the object does not exist, set it to null
    if (!object) {
      object = null;
    }

    // Return final shrunk down object
    return object;
  }

  /**
   * Enforces the storage structure of suarayData
   *
   * @return {void}
   */
  function enforceDataStructure(localStorageData) {

    // Variable declarations
    var verifiedData, defaultKey;

    // First, check if we have any data
    if (!localStorageData) {

      // Return the suaray defaults
      return suarayDataDefaults;
    }

    // Set verified data
    verifiedData = {};

    // For every key in suarayDataDefaults
    for (defaultKey in suarayDataDefaults) {

      // Make sure this suarayDataDefaults property is a property
      if (suarayDataDefaults.hasOwnProperty(defaultKey)) {

        // If localStorageData has this very same key
        if (localStorageData.hasOwnProperty(defaultKey)) {

          // Add to verifiedData
          verifiedData[defaultKey] = localStorageData[defaultKey];

        // If key is missing
        } else {

          // Add missing key
          verifiedData[defaultKey] = suarayDataDefaults[defaultKey];
        }
      }
    }

    // Return verified data structure
    // this will remove any unwanted or old keys from our local storage
    return verifiedData;
  }

  /**
   * Load and return stored data
   *
   * @return object
   */
  function getData() {

    // Get data from HTML5 storage
    /*global localStorage*/
    var suarayData = localStorage.getItem('suarayData');

    // Parse data into object
    suarayData = JSON.parse(suarayData);

    // Enfore data structure
    suarayData = enforceDataStructure(suarayData);

    // Return data
    return suarayData;
  }

  // Methods available for storage provider
  return {

    // Configurable settings

    // Injectible stuff
    $get: function () {

      /**
       * Get all suarayData
       *
       * @return {object} All available config options
       */
      function getAll() {

        // Return data
        return getData();
      }

      /**
       * Get a specific storage key value
       *
       * @param {string} search     The key name or dot notation search of the data to be found
       */
      function get(search) {

        // Get data
        var suarayData = getData();

        // Return selected dot notation search
        return getProperty(suarayData, search);
      }

      /**
       * Set a specific storage key value
       *
       * @param {string} key     The key name of the config value you want to set
       * @param {string} value   The actual value of the config settings
       */
      function set(key, value) {

        // Get data
        var suarayData = getData();

        // Set key value pair
        suarayData[key] = value;

        // Stringify object
        suarayData = JSON.stringify(suarayData);

        // Save to HTML5 storage
        localStorage.setItem('suarayData', suarayData);
      }

      // Return all methods encapsulated in this parent function
      return {
        getAll: getAll,
        set: set,
        get: get
      };

    }

  };

});
