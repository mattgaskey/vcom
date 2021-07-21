Use this pattern to arrange other patterns in one of many possible grid layouts.

Size variants, for example `.grid--large` and `.grid--small`, refer to the *size of the grid cells*. Meaning that cells inside `.grid--small` are smaller than the default (therefore creating more columns).

By default, `.grid` will maintain a specific number of columns per row (which will vary at screen sizes). The default number of columns can be set in the configuration file. For Emory Winship, the default column number per row is 3. Adjust the number of columns per row via the size classes. The Winship library provides these column scaling options:

|Variant|Cells per row|
|---|---|
|`.grid--xsmall`|5|
|`.grid--small`|4|
|`.grid` (default/unmodified)|3|
|`.grid--large`|2|
|`.grid--xlarge`|1|

The `.grid--xlarge` variant will always create a single-column stack at all screen sizes. This option exists to support visual styles needed by other patterns, as many patterns include the grid pattern. For example, a news list may appear as a three column/default grid on a landing page, but as a single column in a feed or sidebar pattern.

The `--flexible` option prioritizes the width of the cells instead of the number of columns per row. Meaning that `.grid--flexible` will fit as many cells as possible in a row.

Use `.grid--narrow-gutters` with any grid option to reduce space between cells.

The `grid--major-left` and `grid--major-right` options exist to create uneven columns with two columns per row. Generally these are extended by the `.sidebar` pattern to create sidebar pages, but can be used by any pattern to make an uneven sidebar-style split.

Use `.grid--ruled` to add lines between grid items.
