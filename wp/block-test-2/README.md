# block 

if in block.json there's a property `editorScript` it will be loaded as a module in the editor.
* WP is also looking for another file called block.asset.php
  * (errors if not found... and block is not working anymore...)
  * if JS filename is block.js then WP will look for PHP file block.asset.php
```
    ...
    "editorScript": "file:./block.js",
    ...
```