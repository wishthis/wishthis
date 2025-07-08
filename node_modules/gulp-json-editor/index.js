var jsbeautify   = require('js-beautify').js_beautify;
var deepmerge    = require('deepmerge');
var through      = require('through2');
var PluginError  = require('plugin-error');
var detectIndent = require('detect-indent');

module.exports = function(editor, jsbeautifyOptions, deepmergeOptions) {

  /*
   * deepmerge options
   */
  deepmergeOptions = deepmergeOptions || {};

  /*
   * create 'editBy' function from 'editor'
   */
  var editBy;
  if (typeof editor === 'function') {
    // edit JSON object by user specific function
    editBy = function(json) { return editor(json); };
  } else if (typeof editor === 'object') {
    // edit JSON object by merging with user specific object
    editBy = function(json) { return deepmerge(json, editor, deepmergeOptions); };
  } else if (typeof editor === 'undefined') {
    throw new PluginError('gulp-json-editor', 'missing "editor" option');
  } else {
    throw new PluginError('gulp-json-editor', '"editor" option must be a function or object');
  }

  /*
   * js-beautify options
   */
  jsbeautifyOptions = jsbeautifyOptions || {};

  /*
   * create through object and return it
   */
  return through.obj(function(file, encoding, callback) {
    var self = this;

    // ignore it
    if (file.isNull()) {
      this.push(file);
      return callback();
    }

    // stream is not supported
    if (file.isStream()) {
      this.emit('error',
        new PluginError('gulp-json-editor', 'Streaming is not supported'));
      return callback();
    }

    // when edit fail
    var onError = function(err) {
      self.emit('error', new PluginError('gulp-json-editor', err));
      callback();
    };

    try {
      // try to get current indentation
      var indent = detectIndent(file.contents.toString('utf8'));

      // beautify options for this particular file
      var beautifyOptions = deepmerge({}, jsbeautifyOptions); // make copy
      beautifyOptions.indent_size = beautifyOptions.indent_size || indent.amount || 2;
      beautifyOptions.indent_char = beautifyOptions.indent_char || (indent.type === 'tab' ? '\t' : ' ');
      beautifyOptions.beautify = !('beautify' in beautifyOptions && !beautifyOptions.beautify);

      // when edit success
      var onSuccess = function(json) {
        json = JSON.stringify(json);
        
        // beautify JSON
        if (beautifyOptions.beautify) {
          json = jsbeautify(json, beautifyOptions);
        }
  
        // write it to file
        file.contents = Buffer.from(json);
        self.push(file);
        callback();
      };
  
      // edit JSON object and get it as string notation
      var res = editBy(JSON.parse(file.contents.toString('utf8')));
      if (isPromiseLike(res)) {
        res.then(onSuccess, onError);
      } else {
        onSuccess(res);
      }
    } catch (err) {
      onError(err);
    }
  });
};

function isPromiseLike(maybePromise) {
  return typeof maybePromise === 'object' && maybePromise !== null && typeof maybePromise.then === 'function';
}
