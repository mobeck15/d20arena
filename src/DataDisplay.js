import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";

function DataDisplay({ data, loading }) {
  const [filters, setFilters] = useState({
    name: "",
    type: "",
    cr: "",
    environment: "",
  });

  const handleFilterChange = (e) => {
    const { name, value } = e.target;
    if (value === "All") {
      setFilters({ ...filters, [name]: "" }); // Reset filter to empty string
    } else {
      setFilters({ ...filters, [name]: value });
    }
  };

  const getUniqueEnvironments = () => {
    const uniqueEnvironments = [
      "All",
      ...new Set(data.map((character) => character.environment)),
    ];
    return uniqueEnvironments.sort();
  };

  const getUniqueTypes = () => {
    const uniqueTypes = [
      "All",
      ...new Set(data.map((character) => character.type)),
    ];
    return uniqueTypes.sort();
  };

  const filteredData = data.filter((character) => {
    return Object.keys(filters).every((key) => {
      if (!filters[key]) return true;
      const value = String(character[key]).toLowerCase(); // Convert value to string
      return value.includes(filters[key].toLowerCase());
    });
  });

  if (loading) {
    return <p>Loading...</p>;
  }

  return (
    <div>
      <div>
        <label>
          Name:{" "}
          <input
            type="text"
            name="name"
            value={filters.name}
            onChange={handleFilterChange}
          />
        </label>
        <label>
          Type:{" "}
          <select
            name="type"
            value={filters.type}
            onChange={handleFilterChange}
          >
            {getUniqueTypes().map((typ) => (
              <option key={typ} value={typ}>
                {typ}
              </option>
            ))}
          </select>
        </label>
        <label>
          CR:{" "}
          <input
            type="text"
            name="cr"
            value={filters.cr}
            onChange={handleFilterChange}
          />
        </label>
        <label>
          Environment:{" "}
          <select
            name="environment"
            value={filters.environment}
            onChange={handleFilterChange}
          >
            {getUniqueEnvironments().map((env) => (
              <option key={env} value={env}>
                {env}
              </option>
            ))}
          </select>
        </label>
      </div>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Type</th>
            <th>CR</th>
            <th>Environment</th>
          </tr>
        </thead>
        <tbody>
          {filteredData.map((character, index) => (
            <tr key={index}>
              <td>
                <Link to={`/characters/${index}`}>{character.name}</Link>
              </td>
              <td>{character.type}</td>
              <td>
                {character.cr} - {character.CRAdvancement}
              </td>
              <td>{character.environment}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export default DataDisplay;
