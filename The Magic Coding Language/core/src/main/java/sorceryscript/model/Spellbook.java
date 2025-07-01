package sorceryscript.model;

import java.util.ArrayList;
import java.util.List;
import java.util.stream.Collectors;

/**
 * Spellbook manages a collection of spells.
 */
public class Spellbook {
    private List<Spell> spells = new ArrayList<>();

    public void addSpell(Spell spell) {
        spells.add(spell);
    }

    public void removeSpell(Spell spell) {
        spells.remove(spell);
    }

    public List<Spell> searchByType(String type) {
        return spells.stream().filter(s -> s.getType().equalsIgnoreCase(type)).collect(Collectors.toList());
    }

    public List<Spell> getAllSpells() {
        return spells;
    }
} 