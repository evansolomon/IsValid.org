module.exports = ( grunt ) ->

  grunt.loadNpmTasks 'grunt-contrib-coffee'
  grunt.loadNpmTasks 'grunt-contrib-concat'
  grunt.loadNpmTasks 'grunt-contrib-uglify'
  grunt.loadNpmTasks 'grunt-contrib-cssmin'

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

    coffee:
      glob_to_multiple:
        expand : true
        cwd    : 'js/src'
        src    : '*.coffee'
        dest   : 'js/src/'
        ext    : '.js'

    cssmin:
      compress:
        files:
          'static/css/styles.min.css' : ['css/bootstrap-compiled.min.css', 'css/isvalid.css'],
          'static/css/embed.min.css'  : 'css/embed.css'


  # Default task.
  grunt.registerTask 'default', ['coffee', 'concat', 'uglify', 'cssmin']
