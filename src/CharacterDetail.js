import React, { useState } from "react";
import { Link, useParams } from "react-router-dom";
import StatBlock from "./StatBlock";

function CharacterDetail({ data }) {
  const { index } = useParams();
  const character = data[parseInt(index)];
  const [showRawData, setShowRawData] = useState(false);

  const toggleRawData = () => {
    setShowRawData(!showRawData);
  };

  return (
    <div style={{ display: "flex", flexDirection: "row" }}>
      <div style={{ width: "50%" }}>
        <h2>Character Details</h2>
        <Link to="/">Back to Index</Link>
        {character && <StatBlock key={index} character={character} />}
      </div>
      <div style={{ width: "50%", marginLeft: "20px" }}>
        <button onClick={toggleRawData}>
          {showRawData ? "Hide Raw Data" : "Show Raw Data"}
        </button>
        {showRawData && <pre>{JSON.stringify(character, null, 2)}</pre>}
      </div>
    </div>
  );
}

export default CharacterDetail;
