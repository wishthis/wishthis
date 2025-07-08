
> ### Forked Version using updated dependencies
> Original repo at https://github.com/the-simian/gulp-concat-filenames

# Information

| Package      | gulp-concat-filenames                                                                                 |
|--------------|-------------------------------------------------------------------------------------------------------|
| Description  | Similar to concat, but creates a list of names in the output file, rather than contents being merged. |
| Node Version | >= 12                                                                                                 |

 
---

![gulp-concat-filenames](https://cloud.githubusercontent.com/assets/954596/12309992/609d63da-ba13-11e5-8698-b06ea035f07b.png)


## Usage

### Basic Usage, No options.
```js
var concatFilenames = require('gulp-concat-filenames');

var concatFilenamesOptions = {

};

function fileManifest(){
  gulp
      .src('./lib/**/*.*')
      .pipe(concatFilenames('manifest.txt', concatFilenamesOptions))
      .pipe(gulp.dest('./output/'));
}


gulp.task('file-manifest', fileManifest);

```

###Arguments

Gulp concat-filenames takes 2 arguments: `filename` and `options`

####Filename [**Required**]

 This first argument is the name of the output file the list of filenames will be put into.

#####Options [**Optional**]

The second argument is optional (pun intended), and is an object with the following properties:

- `newline` - The character to use in place of `\n` for a newline. The default value will be `\n`
- `prepend` - Some text to prepend to every entry in the list of filenames
- `append` - Some text to append to every intry in the list of filenames
- `template` - a function that takes one parameter (the file name) and returns the string after some formatting. Can be used in addition to, or instead of, `append` and `prepend`
- `root` - the root folder. Including this argument will return a list of relative paths instead of absolute paths.


###Examples

Given the file structure:

```
.
+-- somefile.txt
+-- lib
|   +-- one.txt
|   +-- two.txt
+-- gulpfile.js

```



```js
var concatFilenames = require('gulp-concat-filenames');

var concatFilenamesOptions = {
    root: '/lib',
    prepend: '==> ',
    append: ' <=='
};

function fileManifest(){
  gulp
      .src('./lib/**/*.*')
      .pipe(concatFilenames('manifest.txt', concatFilenamesOptions))
      .pipe(gulp.dest('./output/'));
}


gulp.task('file-manifest', fileManifest);
```

running `gulp file-manifest` now produces a file called `manifest.txt` with the contents

```
==> one.txt <==
==> two.txt <==

```

Or you can use the template property, to format the output as well.
```js

function fileNameFormatter(filename) {
   return 'XXX--' + filename.toUpperCase() + '--YYY';
}

var concatFilenames = require('gulp-concat-filenames');

var concatFilenamesOptions = {
    root: '/lib',
    template: fileNameFormatter // Pass in a function
};

function fileManifest(){
  gulp
      .src('./lib/**/*.*')
      .pipe(concatFilenames('manifest.txt', concatFilenamesOptions))
      .pipe(gulp.dest('./output/'));
}


gulp.task('file-manifest', fileManifest);
```

running `gulp file-manifest` now produces a file called `manifest.txt` with the contents

```
XXX--ONE.TXT--YYY
XXX--TWO.TXT--YYY

```

###Contribution
If you write tests, follow semver and have something to add, I love accepting pull requests!! Any questions? Make an issue on github! <3
