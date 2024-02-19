const express = require("express");
const fs = require("fs");
const app = express();

// Read JSON data file
const jsonData = fs.readFileSync("data/characters.json", "utf8");
const data = JSON.parse(jsonData);

// Define your API key
const apiKey = "your-api-key"; // Replace 'your-api-key' with your actual API key

// Middleware function to validate API key
function apiKeyAuth(req, res, next) {
  const apiKeyHeader = req.headers["x-api-key"];

  if (!apiKeyHeader || apiKeyHeader !== apiKey) {
    return res.status(401).json({ error: "Unauthorized - Invalid API key" });
  }

  // API key is valid, proceed to the next middleware/route handler
  next();
}

// Apply the middleware to all routes
app.use(apiKeyAuth);

// Route to serve JSON data
app.get("/api/data", (req, res) => {
  res.json(data);
});

// Start the server
const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});
