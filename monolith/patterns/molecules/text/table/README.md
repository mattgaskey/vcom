---
Title: Table
---

Table styles. Tables in an `.editorial` container will get these styles automatically, otherwise the table requires the `content-table` class. This way default browser table styles are not affected.

To be responsive, tables require a wrapper. There are two responsive strategies:

1) For data-intensive or huge tables, the `table-wrapper--overflow` style lets tables maintain their row-column relationships; the whole table is visible on small screens by scrolling in two axis.

2) The `table-wrapper--columnize` table fits `tds` to a grid; columns increase based on the available space. If the browser does not support grid, each td takes up a row. The `thead` is hidden, but the column headers in the `thead` are prepended to the content of each `td` if the included `responsive-table.js` file is included. (If it is not, the table is slightly less accessible but will still work).

For best results for #2, the `thead` should be a single row and no `th` should span more than one column; the cell labeling algorithm is pretty naïve. 