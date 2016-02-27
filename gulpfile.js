var elixir = require('laravel-elixir');
var liveReload = require('gulp-livereload');
var clean = require('rimraf');
var gulp = require('gulp');
var inject = require('gulp-inject');
var gulpSequence = require('gulp-sequence');
var async = require('async');

var config = {
    assets_path: './resources/assets',
    build_path: './public/build'
};

config.views_path = config.assets_path + '/../views';
config.bower_path = config.assets_path + '/../bower_components';

config.build_path_js = config.build_path + '/js';
config.build_vendor_path_js = config.build_path_js + '/vendor';
config.vendor_path_js = [
    config.bower_path + '/jquery/dist/jquery.min.js',
    config.bower_path + '/bootstrap/dist/js/bootstrap.min.js',
    config.bower_path + '/angular/angular.min.js',
    config.bower_path + '/angular-route/angular-route.min.js',
    config.bower_path + '/angular-resource/angular-resource.min.js',
    config.bower_path + '/angular-animate/angular-animate.min.js',
    config.bower_path + '/angular-messages/angular-messages.min.js',
    config.bower_path + '/angular-bootstrap/ui-bootstrap.min.js',
    config.bower_path + '/angular-strap/dist/modules/navbar.min.js',
    config.bower_path + '/angular-cookies/angular-cookies.min.js',
    config.bower_path + '/query-string/query-string.js',
    config.bower_path + '/angular-oauth2/dist/angular-oauth2.min.js',
];

config.build_path_css = config.build_path + '/css';
config.build_vendor_path_css = config.build_path_css + '/vendor';
config.vendor_path_css = [
    config.bower_path + '/bootstrap/dist/css/bootstrap.min.css',
    //config.bower_path + '/bootstrap/dist/css/bootstrap-theme.min.css',
];

config.build_path_html = config.build_path + '/views';
config.build_path_font = config.build_path + '/fonts';
config.build_path_image = config.build_path + '/images';

gulp.task('inject', function () {
    function _transform(filepath, file, i, length) {
        if (filepath.indexOf('/bower_components/') != -1) {
            filepath = config.build_vendor_path_js.replace('.', '') + '/' + filepath.split('/').pop()
        }

        filepath = filepath.replace('/public/', '');

        if (filepath.slice(-3) === '.js') {
            return '<script src="{{ asset(\'' + filepath + '\') }}"></script>';
        }
        else if (filepath.slice(-4) === '.css') {
            return '<link rel="stylesheet" href="{{ asset(\'' + filepath + '\') }}">';
        }

        // Use the default transform as fallback:
        return inject.transform.apply(inject.transform, arguments);
    }

    return gulp.src(config.views_path + '/app.blade.php')
        .pipe(inject(gulp.src(config.vendor_path_js, {read: false}), {
            starttag: '<!-- inject:vendor:{{ext}} -->',
            transform: _transform
        }))
        .pipe(inject(gulp.src(config.build_path_js + '/*.js', {read: false}), {
            transform: _transform
        }))
        .pipe(inject(gulp.src(config.build_path_js + '/controllers/**/*.js', {read: false}), {
            starttag: '<!-- inject:controllers:{{ext}} -->',
            transform: _transform
        }))
        .pipe(inject(gulp.src(config.build_path_js + '/services/**/*.js', {read: false}), {
            starttag: '<!-- inject:services:{{ext}} -->',
            transform: _transform
        }))

        .pipe(inject(gulp.src(config.build_path_css + '/**/*.css', {read: false}), {
            transform: _transform
        }))
        .pipe(gulp.dest(config.views_path));
});

gulp.task('copy-scripts', function (cb) {
    async.series([
            function (callback) {
                gulp.src(config.assets_path + '/js/**/*.js')
                    .pipe(gulp.dest(config.build_path_js))
                    .pipe(liveReload())
                    .on('end', callback);
            },
            function (callback) {
                gulp.src(config.vendor_path_js)
                    .pipe(gulp.dest(config.build_vendor_path_js))
                    .pipe(liveReload())
                    .on('end', callback);
            }
        ],
        function (err) {
            if (err) {
                cb('your error');
            }
            else {
                console.log('success');
                cb();
            }
        });
});

gulp.task('copy-styles', function (cb) {
    async.series([
            function (callback) {
                gulp.src(config.assets_path + '/css/**/*.css')
                    .pipe(gulp.dest(config.build_path_css))
                    .pipe(liveReload())
                    .on('end', callback);
            },
            function (callback) {
                gulp.src(config.vendor_path_css)
                    .pipe(gulp.dest(config.build_vendor_path_css))
                    .pipe(liveReload())
                    .on('end', callback);
            }
        ],
        function (err) {
            if (err) {
                cb('your error');
            }
            else {
                console.log('success');
                cb();
            }
        });
});

gulp.task('copy-html', function (cb) {
    gulp.src([
            config.assets_path + '/js/views/**/*.html'
        ])
        .pipe(gulp.dest(config.build_path_html))
        .pipe(liveReload())
        .on('end', cb);
});

gulp.task('copy-font', function (cb) {
    gulp.src([
            config.assets_path + '/fonts/**/*'
        ])
        .pipe(gulp.dest(config.build_path_font))
        .pipe(liveReload())
        .on('end', cb);
});

gulp.task('copy-image', function (cb) {
    gulp.src([
            config.assets_path + '/images/**/*'
        ])
        .pipe(gulp.dest(config.build_path_image))
        .pipe(liveReload())
        .on('end', cb);
});

gulp.task('clear-build-folder', function () {
    clean.sync(config.build_path);
});

gulp.task('build-default', gulpSequence(
    'clear-build-folder',
    ['copy-html', 'copy-font', 'copy-image']
));

gulp.task('default', function () {
    gulp.start('build-default');

    elixir(function (mix) {
        mix.scripts(
            config.vendor_path_js.concat([config.assets_path + '/js/**/*.js']),
            'public/js/all.js',
            config.assets_path
        );

        mix.styles(
            config.vendor_path_css.concat([config.assets_path + '/css/**/*.css']),
            'public/css/all.css',
            config.assets_path
        );

        mix.version(['js/all.js', 'css/all.css']);
    });
});

gulp.task('build-dev', gulpSequence(
    'clear-build-folder',
    ['copy-scripts', 'copy-styles'], 'inject',
    ['copy-html', 'copy-font', 'copy-image']
));

gulp.task('watch-dev', function () {
    liveReload.listen();
    gulp.start('build-dev');
    gulp.watch(config.assets_path + '/**', ['build-dev']);
});