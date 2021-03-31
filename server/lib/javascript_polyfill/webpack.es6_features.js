module.exports = {
  mode: 'production',

  entry: ['./es/es6_features.js', './dom/dom_features_ie11.js'],

  optimization: {
    minimize: false
  },

  output: {
    path: __dirname + '/src',
    filename: 'es6_dom_features.bundle.js'
  }
};
