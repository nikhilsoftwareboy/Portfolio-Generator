// Force CSS reload by adding a unique timestamp to the CSS file
document.addEventListener('DOMContentLoaded', function() {
  const timestamp = new Date().getTime();
  const stylesheets = document.querySelectorAll('link[rel="stylesheet"]');
  
  stylesheets.forEach(function(stylesheet) {
    if (stylesheet.href.includes('css/style.css')) {
      stylesheet.href = stylesheet.href.split('?')[0] + '?v=' + timestamp;
    }
  });
  
  // Force clear browser cache for CSS
  localStorage.setItem('css_version', timestamp);
  console.log('CSS cache busted at: ' + timestamp);
}); 