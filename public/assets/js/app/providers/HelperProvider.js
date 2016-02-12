/*jslint unparam: true*/
/*global app,window*/
app.provider('HelperProvider', function () {

  // Invoke strict mode
  "use strict";

  // Methods available for helper provider
  return {

    // Injectible stuff
    $get: function () {

      /**
      * Called in controllers to add to turn strings into slugs for image upload
      *
      * @param event title - of title to turn to string for insertion into URI
      * @return void
      **/
      function slugify(text) {
        return text.toString().toLowerCase()
          .replace(/\s+/g, '-')           // Replace spaces with -
          .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
          .replace(/\-\-+/g, '-')         // Replace multiple - with single -
          .replace(/^-+/, '')             // Trim - from start of text
          .replace(/-+$/, '');            // Trim - from end of text
      }

      // Return all methods encapsulated in this parent function
      return {
        slugify: slugify
      };
    }
  };
});
