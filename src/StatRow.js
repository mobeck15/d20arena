// StatRow.js
import React from "react";

function StatRow({ label, value }) {
  return (
    <tr>
      <th>{label}</th>
      <td>{value}</td>
    </tr>
  );
}

export default StatRow;
