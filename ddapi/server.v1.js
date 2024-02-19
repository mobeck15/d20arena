const express = require("express");
const cors = require("cors"); // Import the cors middleware
const charactersRouter = require("./characters");
const classesRouter = require("./classes");
const sizesRouter = require("./sizes");
const sizedmgRouter = require("./sizedamage");

const app = express();

// Use the cors middleware to enable CORS for all routes
app.use(cors());

// Route for characters data
app.use("/api/characters", charactersRouter);

// Route for classes data
app.use("/api/classes", classesRouter);

// Route for sizes data
app.use("/api/sizes", sizesRouter);
app.use("/api/sizedmg", sizedmgRouter);

// Start the server
const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});
