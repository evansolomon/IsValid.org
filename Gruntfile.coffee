module.exports = ( grunt ) ->

  grunt.loadNpmTasks 'grunt-contrib-coffee'
  grunt.loadNpmTasks 'grunt-contrib-concat'
  grunt.loadNpmTasks 'grunt-contrib-uglify'
  grunt.loadNpmTasks 'grunt-contrib-cssmin'
  grunt.loadNpmTasks 'grunt-text-replace'
  grunt.loadNpmTasks 'grunt-contrib-handlebars'
  grunt.loadNpmTasks 'grunt-casperjs'
  grunt.loadNpmTasks 'grunt-contrib-watch'
  grunt.loadNpmTasks 'grunt-coffeelint'
  grunt.loadNpmTasks 'grunt-exec'

  # Project configuration.
  grunt.initConfig
    meta:
      banner: """
        /* IsValid
         * http://isvalid.org/
         * Copyright (c) <%= grunt.template.today("yyyy") %>
         * Evan Solomon; Licensed MIT
         */

        """

    concat:
      options:
        banner: '<%= meta.banner %>'

      dist:
        src  : [
          'js/vendor/jquery.js'
          'js/vendor/underscore.js'
          'js/vendor/handlebars.runtime.js'
          'js/templates/*.js'
          'js/src/concon.js'
          'js/src/app.js'
        ]
        dest : 'static/js/scripts.js'

    uglify:
      options:
        banner: '<%= meta.banner %>'
      dist:
        src  : '<%= concat.dist.dest %>'
        dest : 'static/js/scripts.min.js'
      bookmarklet:
        src  : 'js/bookmarklet/bookmarklet.js'
        dest : 'js/bookmarklet/bookmarklet.min.js'

    coffee:
      glob_to_multiple:
        expand : true
        cwd    : 'js/src'
        src    : '*.coffee'
        dest   : 'js/src/'
        ext    : '.js'
      compile:
        files:
          'js/bookmarklet/bookmarklet.js' : 'js/bookmarklet/bookmarklet.coffee'

    cssmin:
      options:
        banner: '<%= meta.banner %>'
        keepSpecialComments: 0
      compress:
        files:
          'static/css/styles.min.css' : ['css/bootstrap.css', 'css/isvalid.css'],
          'static/css/embed.min.css'  : 'css/embed.css'

    replace:
      bookmarklet:
        src          : ['templates/footer.php', 'README.md']
        overwrite    : true
        replacements : [
          from : /javascript:[^\n']+/
          to   : ->
            # Use the last line of the minified JS
            bookmarklet = grunt.file.read( 'js/bookmarklet/bookmarklet.min.js' ).split( '\n' ).pop()
            "javascript:#{bookmarklet}"
        ]

    handlebars:
      options:
        namespace   : 'Handlebars.templates'
        processName : ( filename ) ->
          filename.match( /([a-z]+)\.handlebars/ ).pop()
      compile:
        files:
          'js/templates/results.js' : 'js/templates/results.handlebars'
          'js/templates/error.js'   : 'js/templates/error.handlebars'

    casperjs:
      files: ['tests/casperjs/**/*.coffee']

    watch:
      scripts:
        files: ['js/**/*.coffee', 'js/vendor/*']
        tasks: ['coffee', 'concat', 'uglify', 'replace']
        options:
          interrupt: true

      templates:
        files: 'js/**/*.handlebars'
        tasks: ['handlebars', 'concat', 'uglify']

      styles:
        files: 'css/**/*'
        tasks: ['cssmin']

    coffeelint:
      all:
        files: '**/*.coffee'
        options:
          no_tabs: false
          no_empty_param_list: true

    exec:
      start_test_server:
        command: 'php -S localhost:8080 >/dev/null 2>&1 &'
      kill_test_server:
        command: "ps aux | grep 'php -S localhost:8080' | grep -v grep | awk '{print $2}' | xargs kill"

  # Default task.
  grunt.registerTask 'default', ['coffeelint', 'coffee', 'handlebars', 'concat', 'uglify', 'cssmin', 'replace']

  grunt.registerTask 'test', ['exec:start_test_server', 'casperjs', 'exec:kill_test_server']
  grunt.registerTask 'all', ['default', 'test']
