'use strict';

module.exports = function(grunt) {
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    grunt.initConfig({
        nick : 'telebot',
        banner_format: '@charset "UTF-8";\n\n/* <%= pkg.name %> v<%= pkg.version %> --- <%= grunt.template.today("dd mmm yyyy HH:MM:ss o") %> */\n',
        pkg: grunt.file.readJSON('package.json'),
        concat: {
            css_main: {
                options: {
                    stripBanners: {
                        block: true
                    },
                    banner: '<%= banner_format %>'
                },
                src: [
                    'node_modules/purecss/build/base.css',
                    'node_modules/purecss/build/forms.css',
                    'node_modules/purecss/build/buttons.css',
                    'web/assets/css/style.css',
                ],
                dest: 'web/assets/css/<%= nick %>.css'
            },
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1,
                format: 'keep-breaks',
                sourceMap: false
            },
            target: {
                files: {
                    'web/assets/css/<%= nick %>_min.css': ['<%= concat.css_main.dest %>']
                }
            }
        },
    });

    grunt.registerTask('build', ['concat', 'cssmin']);
    grunt.registerTask('default', ['build']);
};
