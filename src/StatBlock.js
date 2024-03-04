// StatBlock.js
import React from "react";
import StatRow from "./StatRow";
import {
  formatSpace,
  formatModifier,
  getSpecialAttackAbilities,
  formatOrganization,
  formatAlignment,
  formatAdvancement,
  formatSkills,
  formatActions,
  statBonus
} from "./utils";
import SpecialAttacks from "./SpecialAttacks";

function StatBlock({ character }) {
  return (
    <div>
      <h2>{character.name}</h2>
      <table>
        <tbody>
          <StatRow
            label="Size/Type:"
            value={
              character.size +
              " " +
              character.type +
              (character.subtype ? " (" + character.subtype + ")" : "")
            }
          />
          <StatRow label="Hit Dice:" value={character.hp.text} />
          <StatRow
            label="Initiative:"
            value={formatModifier(character.initiative)}
          />
          <StatRow label="Speed:" value={formatSpace(character.speed)} />
          <StatRow label="Armor Class:" value={character.ac} />
          <StatRow
            label="Base Attack/Grapple:"
            value={
              formatModifier(character.baseattack) +
              "/" +
              formatModifier(character.grapple)
            }
          />

          <StatRow label="Attack:" value={formatActions(character.actions)} />
          <StatRow
            label="Full Attack:"
            value={formatActions(character.actions)}
          />
          <StatRow
            label="Space/Reach:"
            value={
              formatSpace(character.space, false) +
              "/" +
              formatSpace(character.reach, false)
            }
          />
          <StatRow
            label="Special Attacks:"
            value={getSpecialAttackAbilities(character.actions)}
          />
          <StatRow
            label="Special Qualities:"
            value={character.qualities.join(", ")}
          />
          <StatRow
            label="Saves:"
            value={
              "Fort " +
              formatModifier(character.saves.fort) +
              ", Ref " +
              formatModifier(character.saves.ref) +
              ", Will " +
              formatModifier(character.saves.will)
            }
          />
          <StatRow
            label="Abilities:"
            value={
              "Str " +
              formatModifier(character.stats.str.score) +
              ", Dex " +
              formatModifier(character.stats.dex.score) +
              ", Con " +
              formatModifier(character.stats.con.score) +
              ", Int " +
              formatModifier(character.stats.int.score) +
              ", Wis " +
              formatModifier(character.stats.wis.score) +
              ", Cha " +
              formatModifier(character.stats.cha.score)
            }
          />
          <StatRow label="Skills:" value={formatSkills(character.skills)} />
          <StatRow label="Feats:" value={character.feats.join(", ")} />
          <StatRow label="Environment:" value={character.environment} />
          <StatRow
            label="Organization:"
            value={formatOrganization(character.organization)}
          />
          <StatRow label="Challenge Rating:" value={character.cr} />
          <StatRow label="Treasure:" value={character.treasure} />
          <StatRow
            label="Alignment:"
            value={formatAlignment(character.alignment)}
          />
          <StatRow
            label="Advancement:"
            value={formatAdvancement(character.advancement)}
          />
          <StatRow
            label="Level Adjustment:"
            value={
              formatModifier(character.leveladjust) +
              (character.cohort == true ? " (cohort)" : "")
            }
          />
        </tbody>
      </table>
      <br />
      {character.description}
      <h2>Combat</h2>
      {character.combat}
      <SpecialAttacks specialAttacks={character.actions} />
    </div>
  );
}

export default StatBlock;
