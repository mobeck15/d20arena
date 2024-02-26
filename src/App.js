// App.js
import React, { useState, useEffect } from "react";
import { BrowserRouter as Router, Route, Routes } from "react-router-dom";
import DataDisplay from "./DataDisplay";
import CharacterDetail from "./CharacterDetail";
import axios from "axios";

function App() {
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function fetchData() {
      try {
        let baseURL;

        // Check if the environment is development or production
        if (process.env.NODE_ENV === "development") {
          // Use localhost for development
          baseURL = "http://localhost:8000/ddapi"; // Change port as needed
        } else {
          // Use production URL
          baseURL = "http://dev.stuffiknowabout.com/ddapi";
        }

        // Construct the full URL for the API endpoint
        const url = `${baseURL}/?api=characters&api_key=your_api_key1`;

        // Make the API request
        const response = await axios.get(url);

        // Handle the response
        setData(response.data);
        setLoading(false);
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    }
    fetchData();
  }, []);

  return (
    <Router>
      <Routes>
        <Route
          exact
          path="/"
          element={<DataDisplay data={data} loading={loading} />}
        />
        <Route
          path="/characters/:index"
          element={<CharacterDetail data={data} />}
        />
      </Routes>
    </Router>
  );
}

export default App;
