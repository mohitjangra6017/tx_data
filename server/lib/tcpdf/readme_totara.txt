Totara changes:

* all fonts are included - there will be hopefully admin setting for font selection soon
* extra fallback fonts added
* latest version at the time of release - we need things like SVG support and any bug fixes we can get
* do not forget to update core thirdpartylibs.xml
* fixed the test page http://127.0.0.1/lib/tests/other/pdflibtestpage.php to say Totara

Important:

A new version of the library is being developed. See https://github.com/tecnickcom/tc-lib-pdf

Extra patches:

* TL-10313: support for inline SVG images in TCPDF::openHTMLTagHandler()
* TL-29101: use upstream patches for PHP 8.0 compatibility - https://github.com/tecnickcom/TCPDF/commit/89f9e5f616a8d89417ccc2c50ef67bc78a00235c

Petr Skoda
