/*
Markdown pattern emebds:
<div data-embed="PATTERN-NAME"></div>
**/
(function($) {
  function getDocHeight(doc) {
    doc = doc || document;
    // stackoverflow.com/questions/1145850/
    var body = doc.body, html = doc.documentElement;
    var height = Math.max( body.scrollHeight, body.offsetHeight, 
        html.clientHeight, html.scrollHeight, html.offsetHeight );
    return height;
  }
  
  function setIframeHeight(ifrm) {
    var doc = ifrm.contentDocument? ifrm.contentDocument: 
        ifrm.contentWindow.document;
    ifrm.style.visibility = 'hidden';
    ifrm.style.height = "10px"; // reset to minimal height ...
    // IE opt. for bing/msn needs a bit added or scrollbar appears
    ifrm.style.height = getDocHeight( doc ) + 4 + "px";
    ifrm.style.visibility = 'visible';
  }

  var i = window.location.href.indexOf('/docs/');
  if (i > 0) {
    var base_url = window.location.href.slice(0, i);
    $('[data-embed]').each(function() {
      var $el = $(this);
      var id = $el.data("embed");
      var ext = '';
      if (window.location.href.indexOf('.html') > 0) {
        ext = '.html';
      }
      var url = base_url + '/components/preview/' + id + ext;
      var $iframe = $('<iframe class="component-embed" src="' + url + '">');
      $iframe.on('load', function() {
        setIframeHeight(this);
      });
      $el.html($iframe);
    });
  }

})(jQuery);