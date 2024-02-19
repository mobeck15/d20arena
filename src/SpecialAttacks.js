import React from "react";

function SpecialAttacks({ specialAttacks }) {
  return (
    <div>
      <ul>
        {specialAttacks.map(
          (specialAttack, index) =>
            // Check if the type of special attack is "Special Attack"
            specialAttack.type === "Special Attack" && (
              <li key={index}>
                <strong>
                  {specialAttack.ability} ({specialAttack.nature})
                </strong>
                <br />
                {specialAttack.description}
              </li>
            )
        )}
      </ul>
    </div>
  );
}

export default SpecialAttacks;
