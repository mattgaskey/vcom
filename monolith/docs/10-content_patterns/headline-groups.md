# About headline groups

Headline groups are a common editorial pattern throughout the site, and consist of a headline, text below the headline (a subhead), and text above the headline (a superhead).  

> _Note_: Both the superhead and the subhead are optional, but for obvious reasons a headline should always be provided.

Here is an example of the resulting markup:

```
{{ render '@headline-group' }}
```

And the component:

<div data-embed="headline-group"></div>

This pattern is provided as an alternative to providing separate headline, subhead, and superhead styles. In addition, content managers should be discouraged from combining different `<h>` tags to simulate a headline group because this will result in nonsemantic markup and a violation of [WCAG 2.0 SC 1.3.1](https://www.w3.org/TR/2008/REC-WCAG20-20081211/#content-structure-separation-programmatic).