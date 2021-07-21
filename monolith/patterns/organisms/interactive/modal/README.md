---
Title: Modal
---

Modals are elements that overlay web content and prevent scrolling until dismissed. We are using the magnific-popup library to drive modals. Modals can contain inline blocks, iframes, or images. If modals are defined inline, you need to provide a modal container (but close buttons, overlays are created for you). Iframes only work for youtube, vimeo, and google maps BUT you can provide your own handlers if you have needs beyond those. See the documentation at http://dimsemenov.com/plugins/magnific-popup/documentation.html#iframe-type. 

In Patternlab, define the content of a modal in an `embed` by overriding the `modal_content` block.