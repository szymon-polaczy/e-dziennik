const 
    gulp = require("gulp"),
    sass = require("gulp-sass"),
    sassGlob = require('gulp-sass-glob'),
    postcss = require("gulp-postcss"),
    autoprefixer = require("autoprefixer"),
    sourcemaps = require("gulp-sourcemaps"),
    uglify = require('gulp-uglify'),
    saveLicense = require('uglify-save-license'),
    browserify = require('gulp-browserify'),
    file_size = 'compressed',
    paths = {
        styles: {
            src: "./user-interactions/styles/sass/**/*.sass",
            dest: "./user-interactions/styles/css/"
        },
        scripts: {
            src: "./user-interactions/scripts/src/**/*.js",
            dest: "./user-interactions/scripts/build/",
        }
    };

function styles() {
    return (
        gulp
        .src(paths.styles.src)
        .pipe(sourcemaps.init())
        .pipe(sassGlob())
        .pipe(sass({outputStyle: file_size})).on('error', sass.logError)
        .pipe(postcss([autoprefixer()]))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(paths.styles.dest))
    );
}

function scripts() {
    return (
        gulp
        .src(paths.scripts.src)
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(browserify())
        //.pipe(babel({presets: ['es2015']}))
        .pipe(uglify({output: {comments: saveLicense}}))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(paths.scripts.dest))
    )
}

gulp.task('compile:styles', () => styles());
gulp.task('compile:scripts', () => scripts());
gulp.task('compile:all', gulp.parallel('compile:styles', "compile:scripts"));

gulp.task('watch:styles', () => gulp.watch(paths.styles.src, styles));
gulp.task('watch:scripts', () => gulp.watch(paths.scripts.src, scripts));
gulp.task('watch:all', gulp.parallel('watch:styles', "watch:scripts"));