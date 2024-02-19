const express = require("express");
const fs = require("fs");
const router = express.Router();

// Read JSON tables file
const sizesJson = fs.readFileSync("data/sizes.json", "utf8");
const sizes = JSON.parse(sizesJson);

// Route to serve JSON tables
router.get("/", (req, res) => {
  res.json(sizes);
});

module.exports = router;
