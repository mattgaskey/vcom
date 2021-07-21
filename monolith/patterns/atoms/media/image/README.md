This image template probably isn't valuable for any CMS integration -- they typically have their own mechanisms for making basic image tags. It's primary purpose is as a way to keep image patterns DRY and ensure sensible default values for source and size. 

The `yetimg` tag generates a magicyeti URL. You can feed it a width • height, image ratio and either height or width. 

## Parameters:

* `w`: width
* `h`: height
* `ratio`: a ratio, one of 1:1, 4:3, 3:2, 16:9, 3:4, 2:3, 9:16. You can configure this list in `twig_extensions/yetimg.js`. You can pass `w` or `h` with `ratio.` If you pass both `w` and `h`, `ratio` will be ignored.
* `tag`: an image tag
* `filter`: a image processing filter to use

For more information on tags and filters, see [Magic Yeti](https://magicyeti.us/). 