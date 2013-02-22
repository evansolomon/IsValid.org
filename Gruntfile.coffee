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
        dest : 'static/scripts.js'

    min:
      dist:
        src  : ['<banner:meta.banner>', '<config:concat.dist.dest>']
        dest : 'static/scripts.min.js'

    jshint:
      options:
        curly   : true
        eqeqeq  : true
        immed   : true
        latedef : true
        newcap  : true
        noarg   : true
        sub     : true
        undef   : true
        boss    : true
        eqnull  : true
        browser : true

      globals:
        jQuery: true

    uglify:
      options:
        banner: "<%= meta.banner %>"
      dist:
        src  : ['<%= concat.dist.dest %>']
        dest : 'static/scripts.min.js'

    coffee:
      glob_to_multiple:
        expand : true
        cwd    : 'js/src'
        src    : ['*.coffee']
        dest   : 'js/src/'
        ext    : '.js'

    cssmin:
      compress:
        files:
          "static/styles.css" : ["css/bootstrap-compiled.min.css", "css/isvalid.css"],
          "static/embed.css"  : ["css/bootstrap-compiled.min.css", "css/isvalid.css", "css/embed.css"]


  # Default task.
  grunt.registerTask 'default', ['coffee', 'concat', 'uglify', 'cssmin']
