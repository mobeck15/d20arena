const express = require("express");
const fs = require("fs");
const router = express.Router();

// Read JSON tables file
const classesJson = fs.readFileSync("data/classes.json", "utf8");
const classes = JSON.parse(classesJson);

// Route to serve JSON tables
router.get("/", (req, res) => {
  res.json(classes);
});

module.exports = router;
