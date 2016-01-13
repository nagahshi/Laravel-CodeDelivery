//Globals Variables
var elixir = require('laravel-elixir'),
    liveReload = require('gulp-livereload'),
    clean = require('rimraf'),
    gulp = require('gulp');

var config = {
    assets_path: './resources/assets',
    build_path:  './public/build',
    bower_path:  './resources/bower_components',
};

//Java Scripts
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
	config.bower_path + '/angular-bootstrap/ui-bootstrap-tpls.js',
    config.bower_path + '/angular-strap/dist/modules/navbar.min.js',
    config.bower_path + '/angular-cookies/angular-cookies.min.js',
    config.bower_path + '/query-string/query-string.js',
    config.bower_path + '/angular-oauth2/dist/angular-oauth2.min.js',
    config.bower_path + '/ng-file-upload/ng-file-upload.min.js',
    config.bower_path + '/angular-http-auth/src/http-auth-interceptor.js',
    config.bower_path + '/angularUtils-pagination/dirPagination.js',
    config.bower_path + '/pusher/dist/pusher.min.js',
    config.bower_path + '/pusher-angular/lib/pusher-angular.min.js',
    config.bower_path + '/angular-ui-notification/dist/angular-ui-notification.min.js',
    config.bower_path + '/moment/min/moment-with-locales.min.js',
    config.bower_path + '/angular-moment/angular-moment.min.js',
    config.bower_path + '/angular-loading-bar/build/loading-bar.min.js',
    config.bower_path + '/angular-locale-pt-br/angular-locale_pt-br.js',
    config.bower_path + '/blob-util/dist/blob-util.min.js',
];

//CSS Style Sheets
config.build_path_css = config.build_path + '/css';
config.build_vendor_path_css = config.build_path_css + '/vendor';
config.vendor_path_css = [
    config.bower_path + '/bootstrap/dist/css/bootstrap.css',
    config.bower_path + '/bootstrap/dist/css/bootstrap-theme.min.css',
    config.bower_path + '/angular-ui-notification/dist/angular-ui-notification.min.css',
    config.bower_path + '/angular-loading-bar/build/loading-bar.min.css',
	config.assets_path+ './css/styles.css',
];

//HTML, Fonts, Images
config.build_path_html   = config.build_path + '/views';
config.build_path_fonts  = config.build_path + '/fonts';
config.build_path_images = config.build_path + '/images';

//Tasks
gulp.task('copy-fonts', function(){
    gulp.src([
        config.assets_path + '/fonts/**/*'
    ])
        .pipe(gulp.dest(config.build_path_fonts))
        .pipe(liveReload());
});

gulp.task('copy-images', function(){
    gulp.src([
        config.assets_path + '/images/**/*'
    ])
        .pipe(gulp.dest(config.build_path_images))
        .pipe(liveReload());
});

gulp.task('copy-html', function(){
    gulp.src([
        config.assets_path + '/js/views/**/*.html'
    ])
        .pipe(gulp.dest(config.build_path_html))
        .pipe(liveReload());
});

gulp.task('copy-styles', function () {
    //Dev Files
    gulp.src([
        config.assets_path + '/css/**/*.css'
    ])
        .pipe(gulp.dest(config.build_path_css))
        .pipe(liveReload());

    //Vendor Files
    gulp.src(config.vendor_path_css)
        .pipe(gulp.dest(config.build_vendor_path_css))
        .pipe(liveReload());
});

gulp.task('copy-scripts', function () {
    //Dev Files
    gulp.src([
        config.assets_path + '/js/**/*.js'
    ])
        .pipe(gulp.dest(config.build_path_js))
        .pipe(liveReload());

    //Vendor Files
    gulp.src(config.vendor_path_js)
        .pipe(gulp.dest(config.build_vendor_path_js))
        .pipe(liveReload());
});

//Clean
gulp.task('clear-build-folder', function(){
    clean.sync(config.build_path);
});

//Watch
gulp.task('watch-dev',['clear-build-folder'], function(){
    liveReload.listen();
    gulp.start(
        'copy-styles', 'copy-scripts', 'copy-html', 'copy-fonts', 'copy-images'
    );
    gulp.watch(config.assets_path + '/**',[
        'copy-styles', 'copy-scripts', 'copy-html', 'copy-fonts', 'copy-images'
    ]);
});

//Default
gulp.task('default',['clear-build-folder'], function(){
    gulp.start('copy-html', 'copy-fonts', 'copy-images');

    elixir(function(mix){
        mix.styles(
            config.vendor_path_css
            .concat([
                config.assets_path + '/css/**/*.css'
            ]),
            'public/css/all.css', config.assets_path);

        mix.scripts(
            config.vendor_path_js
                .concat([
                    config.assets_path + '/js/**/*.js'
                ]),
            'public/js/all.js', config.assets_path);

        mix.version(['js/all.js', 'css/all.css']);
    });
});