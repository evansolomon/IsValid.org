module.exports = ( grunt ) ->

  grunt.loadNpmTasks 'grunt-contrib-coffee'
  grunt.loadNpmTasks 'grunt-contrib-concat'
  grunt.loadNpmTasks 'grunt-contrib-uglify'
  grunt.loadNpmTasks 'grunt-contrib-cssmin'
  grunt.loadNpmTasks 'grunt-text-replace'

  # Project configuration.
  grunt.initConfig
    meta:
      banner: """
        /* IsValid
         * http://isvalid.org/
         * Copyright (c) <%= grunt.template.today("yyyy") %>
         * Evan Solomon; Licensed GPL
         */

        """

    concat:
      options:
        banner: '<%= meta.banner %>'

      dist:
        src  : ['js/vendor/jquery.js', 'js/vendor/underscore.js', 'js/vendor/handlebars.js', 'js/src/isvalid.js']
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


  # Default task.
  grunt.registerTask 'default', ['coffee', 'concat', 'uglify', 'cssmin', 'replace']
