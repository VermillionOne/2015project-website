module.exports = (grunt) ->

  grunt.initConfig
    # #########################################################
    # # jsHint - Lint checker config for js files (angular) 
    # #########################################################
    jshint:
      
      ignore_warning:

        options:
          '-W083': true

        src: ['public/assets/js/app/**/*.js']

    # #########################################################
    # # Clean - empties out anything inside the following folder
    # #########################################################
    clean:

      # /dist/ folder
      dist: ["public/assets/dist/*"]

      # /dist/*.js folder
      dist_js: ["public/assets/dist/*.js"]

      # /dist/*.css folder
      dist_css: ["public/assets/dist/*.css"]

      # /bower_components/ folder
      bower: ["public/assets/bower_components/*"]

    # #########################################################
    # # Bower - config options needed by the "bower:install" and "bower:prune" tasks
    # #########################################################
    bower:
      install:
        options:
          verbose: true
          copy: false

    # #########################################################
    # # Copy - Copy over necessary over to the /dist/ folder
    # #########################################################
    copy:

      # Copy 1 single file needed by angular when developer console is opened to dist folder
      angular_map:
        expand: true
        cwd: 'public/assets/bower_components/angular/',
        src: [
          'angular.min.js.map',
        ],
        dest: 'public/assets/dist/'

    # #########################################################
    # # Less - translates app.less into 1 concatonated vanilla css file
    # #########################################################
    less:
      stuff:
        options:
          cleancss: false
          sourceMap: true
        files:
          'public/assets/dist/suaray.css': [
            'public/assets/less/app.less'
          ]

    # #########################################################
    # # Concatenate all files to dist folder
    # #########################################################
    concat:

      # Lib Global - global js files that should be included in every page (i.e master.blade.php)
      lib_global:
        src: [
          'public/assets/bower_components/bugsnag/src/bugsnag.js',
          'public/assets/bower_components/jquery/dist/jquery.min.js',
          'public/assets/bower_components/jquery-ui/jquery-ui.min.js',
          'public/assets/bower_components/bootstrap/dist/js/bootstrap.min.js',
          'public/assets/bower_components/angular/angular.min.js'
        ]
        dest: 'public/assets/dist/lib-global.js'

      # Lib Home - home page only
      lib_home:
        src: [
          'public/assets/bower_components/bxslider-4/jquery.bxslider.min.js',
          'public/assets/bower_components/leaflet/dist/leaflet.js',
          'public/assets/bower_components/L.GeoSearch/src/js/l.control.geosearch.js',
          'public/assets/bower_components/L.GeoSearch/src/js/l.geosearch.provider.esri.js',
        ]
        dest: 'public/assets/dist/lib-home.js'

      # Lib Browse - browse page only
      lib_browse:
        src: [
          'public/assets/bower_components/bxslider-4/jquery.bxslider.min.js',
          'public/assets/bower_components/leaflet/dist/leaflet.js',
          'public/assets/bower_components/L.GeoSearch/src/js/l.control.geosearch.js',
          'public/assets/bower_components/L.GeoSearch/src/js/l.geosearch.provider.esri.js',
        ]
        dest: 'public/assets/dist/lib-browse.js'

      # Lib Mobile App Landing Page - Mobile App Landing Page page only
      lib_mobile:
        src: [
          'public/assets/bower_components/bxslider-4/jquery.bxslider.min.js',
          'public/assets/bower_components/leaflet/dist/leaflet.js',
          'public/assets/bower_components/L.GeoSearch/src/js/l.control.geosearch.js',
          'public/assets/bower_components/L.GeoSearch/src/js/l.geosearch.provider.esri.js',
        ]
        dest: 'public/assets/dist/lib-mobile.js'

      # Lib events show - event detail
      lib_events_show:
        src: [
          'public/assets/bower_components/bxslider-4/jquery.bxslider.min.js',
          'public/assets/bower_components/fancybox/source/jquery.fancybox.js',
          'public/assets/bower_components/flipclock/compiled/flipclock.js',
          'public/assets/bower_components/codemirror/lib/codemirror.js',
          'public/assets/bower_components/jquery.markdownify/lib/jquery.markdownify.js',
          'public/assets/bower_components/marked/lib/marked.js'
        ]
        dest: 'public/assets/dist/lib-events-show.js'

      # Lib events create - event creation
      lib_events_create:
        src: [
          'public/assets/bower_components/evaporatejs/evaporate.js',
          'public/assets/bower_components/codemirror/lib/codemirror.js',
          'public/assets/bower_components/jquery.markdownify/lib/jquery.markdownify.js',
          'public/assets/bower_components/marked/lib/marked.js',
          'public/assets/bower_components/codemirror/mode/markdown/markdown.js',
          'public/assets/bower_components/codemirror/addon/edit/continuelist.js',
          'public/assets/bower_components/codemirror/mode/xml/xml.js'
        ]
        dest: 'public/assets/dist/lib-events-create.js'

      # Lib accounts profile - user profile
      lib_accounts_profile:
        src: [
          'public/assets/bower_components/jquery-ui/jquery-ui.min.js',
          'public/assets/bower_components/moment/min/moment.min.js',
          'public/assets/bower_components/fullcalendar/dist/fullcalendar.js',
          'public/assets/bower_components/bxslider-4/jquery.bxslider.min.js',
          'public/assets/bower_components/evaporatejs/evaporate.js',
        ]
        dest: 'public/assets/dist/lib-accounts-profile.js'

      # Lib accounts profile - user profile
      lib_payment:
        src: [
          'public/assets/bower_components/bootstrap/js/tab.js',
        ]
        dest: 'public/assets/dist/lib-payment.js'

      # Angular suaray app specific files
      suaray:
        src: [
          'public/assets/js/app/app.js',
          'public/assets/js/app/**/*.js',
        ]
        dest: 'public/assets/dist/suaray.js'

    # #########################################################
    # # "grunt watch" - watch all .less and .js files and execute the "grunt update" command if one of those files changes
    # #########################################################
    watch:

      # Watches all config files (coffeee, bower)
      configs:
        files: [
          'Gruntfile.coffee',
          'bower.json',
        ]
        tasks: [
          'update_all',
        ]
        options:
          interrupt: true

      # Watches all js files
      js:
        files: [
          'public/assets/js/**/*',
        ]
        tasks: [
          'clean:dist_js',
          'copy',
          'concat',
        ]
        options:
          interrupt: true

      # Watches all css / less files
      css:
        files: [
          'public/assets/less/**/*',
        ]
        tasks: [
          'clean:dist_css',
          'less',
        ]
        options:
          interrupt: true

  # #########################################################
  # # Any required grunt node packages needed by this file should be listed
  # # updated package.json if you need any specific grunt npm modules
  # #########################################################
  grunt.loadNpmTasks 'grunt-ssh'
  grunt.loadNpmTasks 'grunt-contrib-jshint'
  grunt.loadNpmTasks 'grunt-contrib-clean'
  grunt.loadNpmTasks 'grunt-contrib-less'
  grunt.loadNpmTasks 'grunt-contrib-copy'
  grunt.loadNpmTasks 'grunt-contrib-watch'
  grunt.loadNpmTasks 'grunt-contrib-coffee'
  grunt.loadNpmTasks 'grunt-contrib-concat'
  grunt.loadNpmTasks 'grunt-bower-task'
  grunt.loadNpmTasks 'grunt-git'
  grunt.loadNpmTasks 'grunt-composer'
  grunt.loadNpmTasks 'grunt-aws-s3'

  # #########################################################
  # # "grunt linter" task - executes jsHint grunt task 
  # #########################################################
  grunt.registerTask 'linter', ['jshint']

  # #########################################################
  # # Runs all update groups at once
  # #########################################################
  grunt.registerTask 'update_all', [
    'clean',
    'bower:install',
    'copy',
    'concat',
    'less',
  ]

  # #########################################################
  # # "grunt update" task - executes the update_all task
  # #########################################################
  grunt.registerTask 'update', ['update_all']
