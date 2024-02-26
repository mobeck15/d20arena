const express = require("express");
const fs = require("fs");
const router = express.Router();

// Read JSON tables file
const sizedmgJson = fs.readFileSync("data/damagesize.json", "utf8");
const sizedmg = JSON.parse(sizedmgJson);

// Route to serve JSON tables
router.get("/", (req, res) => {
  res.json(sizedmg);
});

module.exports = router;
