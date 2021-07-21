# Accessible SVGs

This is a pattern for including inline code for SVGs. SVGs included this way are very handy in some ways; for example, the fill can inherit the current color of an element (with `fill=currentColor`), or parts of the SVG can be targeted with style sheets.

Accessibility and scaling is a bit of a bear, however.

## Accessibility

I've taken the research described at [Simply Accessible](https://simplyaccessible.com/article/7-solutions-svgs/) and used that to make sure graphics provided by inline SVG are reliably announced to browsers. The typical way (`aria-labeledby`) or the expected way (`title` apparently don't work in all screen readers. Instead, we place the SVG in a container flagged as `aria-hidden` so it is ignored by screen readers, then provide alternative text via content that is hidden from screen display only.

## Scaling

SVG scaling is counter-intuitive. [Excellent discussion how and why here](https://css-tricks.com/scale-svg/). The upshot if it is that you will need to create styles that explicitly define height and width, either as precise measurements (px, em, rem) or more relative measurements (`vh`, `vw`, `vmin`). If you use `vh` or `vw`, make sure you use the same unit for both the height and the width to ensure the units stay the same size in both dimension, otherwise you won't get a reliable aspect ratio. This is really only useful for design elements that are SVG and you can more or less pin down a range of sizes for. The `width: 100%; height: auto;` trick you do with images simply doesn't work.