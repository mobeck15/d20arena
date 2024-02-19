const express = require("express");
const fs = require("fs");
const router = express.Router();

// Read JSON data file
const charactersJson = fs.readFileSync("data/characters.json", "utf8");
let characters = JSON.parse(charactersJson);

// Loop through each character and add default values if necessary
characters.forEach((character) => {
  if (!character.treasure) {
    character.treasure = "None";
  }
  if (!character.leveladjust) {
    character.leveladjust = "—";
  }
});

const charactersWithCRAdvancement = characters.map((character) => {
  let maxHD = character.cr; // Set maxHD to current CR value by default

  if (
    Array.isArray(character.advancement) &&
    character.advancement.length > 0
  ) {
    maxHD = Math.max(...character.advancement.map((adv) => adv.highhd));
  }

  const CRIncrease = Math.floor(maxHD / 4);
  const CRAdvancement = character.cr + CRIncrease;
  return { ...character, CRAdvancement };
});

// Route to serve JSON data
router.get("/", (req, res) => {
  res.json(charactersWithCRAdvancement);
});

module.exports = router;
