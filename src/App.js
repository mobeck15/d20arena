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
        const response = await axios.get(
          "http://localhost:5000/api/characters"
        );
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
