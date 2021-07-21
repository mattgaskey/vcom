# Updating the Footer

Some portions of the footer are under control of the CMS, while other sections are managed in Fractal patterns depending on our expectation of regular change.

Changes to the footer change every page in the site.

## Menu blocks

The four menu blocks in the footer are managed as Blocks in Drupal. Each menu is a simple list of links and does not update automatically, even if there are menus (like "Main Nav") that share similar names. This lets you add or remove links in this section only without affecting other navigation structures or the site document tree.

## Locations and related social media accounts

The locations and related social media accounts are not expected to change often or at all, so the content has not been put under CMS control. If you must update this information, you can change the `organisms/clamp/footer` Fractal pattern. Address information is stored in a JSON-like data structure passed to the address template; the same is true for social media accounts. The design allows up to three social media accounts for each location.

## Copyright statement and legal navigation

The copyright date changes automatically on the new year; no intervention is necessary to update this. Legal notice navigation ("Accessibility, Privacy Notice") is also editable in the the `organisms/clamp/footer` Fractal pattern as plain HTML.


