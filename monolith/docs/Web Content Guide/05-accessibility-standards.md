# Accessibility Standards
As of January 2018, colleges, universities, and other agencies that receive Federal funding are required to meet [WCAG 2.1 AA-level standards](https://www.w3.org/WAI/standards-guidelines/) for making sure their information and communication technologies are accessible to people with disabilities. To comply with [Section 508](https://www.section508.gov/manage/laws-and-policies) of the law, websites, documents, images, video, and audio must be accessible to users who have sight or hearing limitations, color blindness, seizures, and physical limitations that require them to navigate with a keyboard instead of a mouse. 
Many accessibility issues arise from the content itself or from the way it is formatted into the CMS, so anyone who creates or publishes digital content should be familiar with these standards and learn how to adjust their content to meet them.

## Accessibility Training Resources
* [Web Accessibility Initiative tutorials](https://www.w3.org/WAI/tutorials/) from the W3C, an international community that develops open standards to ensure long-term growth of the web
* [Accessibility articles at WebAIM.org](https://webaim.org/articles/), a non-profit at Utah State University focused on web accessibility training
* [Accessibility guides from the A11y Project](https://a11yproject.com/), an open source community aimed at making accessibility easier to implement on the web
* [Government Accessibility guide from 18F](https://accessibility.18f.gov/), a digital services agency within the U.S. government’s General Services Administration
* [Guide to help you find a screen reader](https://developer.paciellogroup.com/blog/2015/01/basic-screen-reader-commands-for-accessibility-testing/) compatible with your system from the Paciello Group, an accessibility consulting firm 

## Automated Accessibility Checkers
There are online tools and browser extensions available (many for free) to help uncover accessibility errors and suggest ways to correct them. Most accessibility issues are contextual, content-dependent, and can come down to a matter of human judgment, so [automated tools will only catch a portion of them](https://alphagov.github.io/accessibility-tool-audit/). Also, different tools often find different errors, so it’s a good idea to use more than one. 
* [WAVE - WebAIM.org’s web accessibility evaluation tool and browser extension](http://wave.webaim.org/)
* [Axe browser extension](https://www.deque.com/axe/) 
* [SiteImprove browser extension](https://siteimprove.com/en-us/core-platform/integrations/browser-extensions/)

## Common Accessibility Issues for Content
### Built-in Heading Styles
Screen reading software uses the CMS’s built-in heading styles to navigate the page, so use the heading styles H2 through H6 provided in the drop-down menu in the WYSIWYG text field to designate headings and subheadings in your text, rather than making your own heading styles using bold, Italics, or underline. Likewise, do not use CMS heading styles to emphasize text that is not meant to serve as a heading. 
### Hierarchical Headings
Structure your pages in a hierarchical manner, with H1 followed by subsequent heading levels in order as needed to denote subsections of text. Do not skip a level (e.g., jumping from H2 to H4) just for visual effect.
For example:
# H1 - page title
## H2 - major section 
### H3 - subsection 
#### H4 - sub-subsection 
### H3 - a second subsection 
## H2 - a second major section 

Every web page must have an H1 style to designate its page title, and the title must also be the only place H1 is used on the page. You won’t usually find H1 as an option in rich text style selectors because it’s generated automatically by the CMS when you fill in the title field.
[See WebAIM’s recommendations](https://webaim.org/techniques/semanticstructure/#contentstructure) for more about headings. 

### Alternative Text for Images
Alternative text (a.k.a. an alt tag) is a short string of descriptive text that must be included with each image you upload into the CMS. Alternative text is used in three ways:
* By screen reading software, to help users with sight or other limitations understand what’s in an image and what it’s doing on the page
* By web browsers, to appear in place of an image when the internet connection is too slow or intermittent to load the page properly, or for users who have chosen to turn off image loading to save bandwidth
* By search engines, to select images for search results and help associate page content with certain keywords for search ranking purposes

A good alt tag:
1. Describes the image in plain language
2. Does not use “keyword stuffing” 
3. Is no more than 16 words long


**Default filename**: DCM3090.jpg

**Better web filename**: whale-watching.jpg

**Keyword-stuffed alt tag**: Enjoy exciting alumni vacations watching whale migration and exotic wild animals, book now for best price

**Better alt tag**: Humpback whale’s tail splashes near small boat of onlookers

For more, see [WebAIM’s guide to writing alternative text](https://webaim.org/techniques/alttext/). 

### Tables
Tables can be difficult for screen readers and keyboard navigators to parse. There are a lot of considerations for [making tables accessible](https://webaim.org/techniques/tables/). A few general rules include:
* Only use them for tabular data (where information in the cells semantically maps to the respective column and row headers), not as a layout tool for emphasizing text or creating multiple columns. 
* Keep table structures simple, using the smallest number of columns possible; try breaking them into multiple smaller tables instead of making one big complex one. If you can eliminate the table entirely and use a series of lists, even better.
* Don’t merge cells to make them span multiple rows or columns. Screen readers pair headings with cell data based on their relative positions, so cells that span columns won’t be read correctly. 

### PDFs
For PDF files to meet accessibility standards, they must: 
* Be searchable
* Use accessible fonts
* Contain tab markers, links, and bookmarks to help users navigate 
* Have interactive form fields with labels and accessible error messages, if they’re a form
* Include image alt tags and other metadata 

Converting inaccessible PDFs to ones that meet accessibility standards can be time-consuming and costly. It’s often best to eliminate PDFs wherever you can and publish information in HTML format using your CMS instead. For more information, see [WebAIM’s guide on creating accessible PDFs](https://webaim.org/techniques/acrobat/).

### Videos, PowerPoint, and Other Multimedia 
Any non-text content must have a text alternative available for people who need to access it in a different format. 
* [Captions, transcripts, and sometimes audio descriptions](https://webaim.org/techniques/captions/) must be provided for pre-recorded video and audio content.  
* [PowerPoint and other slide presentation formats](https://webaim.org/techniques/powerpoint/) can be very complicated to make accessible for the web. Use the software’s internal accessibility checker to find avoidable errors, or consider converting to another format such as an accessible PDF. 