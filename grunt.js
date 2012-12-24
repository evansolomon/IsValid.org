/*global module:false*/
module.exports = function(grunt) {

  grunt.loadNpmTasks('grunt-coffee');

  // Project configuration.
  grunt.initConfig({
    meta: {
      banner: '/*! IsValid ' +
        '<%= grunt.template.today("yyyy-mm-dd") %>\n' +
        '* http://isvalid.org/\n' +
        '* Copyright (c) <%= grunt.template.today("yyyy") %> ' +
        'Evan Solomon; Licensed GPL */'
    },
    concat: {
      dist: {
        src: ['<banner:meta.banner>', 'js/vendor/jquery.js', 'js/vendor/underscore.js', 'js/vendor/handlebars.js', 'js/src/isvalid.js'],
        dest: 'static/scripts.js'
      }
    },
    min: {
      dist: {
        src: ['<banner:meta.banner>', '<config:concat.dist.dest>'],
        dest: 'static/scripts.min.js'
      }
    },
    jshint: {
      options: {
        curly: true,
        eqeqeq: true,
        immed: true,
        latedef: true,
        newcap: true,
        noarg: true,
        sub: true,
        undef: true,
        boss: true,
        eqnull: true,
        browser: true
      },
      globals: {
        jQuery: true
      }
    },
    uglify: {},
    coffee: {
      app: {
        src: ['js/src/*.coffee'],
        dest: 'js/src/',
        options: {
            bare: false
        }
      }
    }
  });

  // Default task.
  grunt.registerTask('default', 'coffee concat min');

};
