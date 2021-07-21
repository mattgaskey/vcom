---
Title: Headline Group
---

This pattern expresses a headline group -- a headline that is composed of a headline and either a superhead, subhead, or possibly both. It can be either semantic (using `H#` tags) or non-semantic (with a `div` tag). Either way, the super/subheads are contained within the header tag so they maintain a semantic relationship with the header text. (As opposed to using smaller-level `H#` tags to represent super/subheads).

Headline groups wrapped in a semantic `H#` element should inherit text styles from the base `H#` tag for the headline text.

Variables:

* _superhead_ : Text that goes above the headline. Default: none, but pass "true" to get `fake('company.bs')` text generated for you.
* _subhead_ : Text that goes below the headline. Default: none, but pass "true" to get `fake('company.bs')` text generated for you.
* _headline_ : The headline text. Default: `fake(bs)`
* _semantic_ : Whether or not to use an `h#` tag as the containing element. Default: true
* _level_ : If `semantic`, what level headline to use. Default: 2
* _linked_ : Whether or not the headline is linked. Default: `false`
* _style_modifier_ : Pass additional class names here. Create a matching `json` file to expose this variant.
