// utils.js
export function formatSpace(speed, feet = true) {
  const space = `${speed} ft.`;
  const squares = `(${speed / 5} squares)`;
  if (feet) {
    return `${space} ${squares}`;
  } else {
    return `${space}`;
  }
}

export function formatModifier(number) {
  if (number > 0) {
    return `+${number}`;
  } else {
    return `${number}`;
  }
}

export function getSpecialAttackAbilities(actions) {
  const specialAttacks = actions
    .filter((action) => action.type === "Special Attack")
    .map((action) => action.ability);

  return specialAttacks.join(", ");
}

export function formatOrganization(organization) {
  // Sort organizations by the low value
  organization.sort((a, b) => a.low - b.low);

  const formattedOrganizations = organization.map((org) => {
    if (org.low === org.high) {
      return `${org.name} (${org.low})`;
    } else {
      return `${org.name} (${org.low}-${org.high})`;
    }
  });

  // Join formatted organizations using commas and 'or' for the last one
  let result = "";
  for (let i = 0; i < formattedOrganizations.length; i++) {
    result += formattedOrganizations[i];
    if (i < formattedOrganizations.length - 2) {
      result += ", ";
    } else if (i === formattedOrganizations.length - 2) {
      result += " or ";
    }
  }

  return result;
}

export function formatAlignment(alignment) {
  const structure = alignment.structure;
  const moral = alignment.moral;

  if (structure === "neutral" && moral === "neutral") {
    return `${alignment.frequency} neutral`;
  } else {
    return `${alignment.frequency} ${structure} ${moral}`;
  }
}

export function formatAdvancement(advancement) {
  // Check if advancement is an array
  if (!Array.isArray(advancement)) {
    return advancement; // Return unmodified value if it's not an array
  }

  // If advancement is an array, proceed with formatting
  const formattedAdvancements = advancement.map((adv) => {
    return `${adv.lowhd}-${adv.highhd} HD (${adv.size})`;
  });

  return formattedAdvancements.join("; ");
}

export function formatSkills(skills) {
  return skills
    .map((skill) => {
      const skillName = Object.keys(skill)[0]; // Extract skill name
      const skillValue = skill[skillName]; // Extract skill value

      // Capitalize skill name and add a "+" sign for positive values
      const formattedSkillName =
        skillName.charAt(0).toUpperCase() + skillName.slice(1);
      const formattedSkillValue =
        skillValue >= 0 ? `+${skillValue}` : skillValue;

      return `${formattedSkillName} ${formattedSkillValue}`;
    })
    .join(", ");
}

export function formatActions(actions) {
  // Filter actions with type "Attack"
  const attackActions = actions.filter((action) => action.type === "Attack");

  // Format filtered attack actions
  return attackActions
    .map((action) => {
      const { ability, bonus, damagetype, damage } = action;
      return `${ability} +${bonus} ${damagetype} (${damage})`;
    })
    .join(" or ");
}
