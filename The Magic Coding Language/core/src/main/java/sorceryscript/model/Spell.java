package sorceryscript.model;

import java.util.Map;

/**
 * Spell represents a magical spell with metadata and parameters.
 */
public class Spell {
    private String name;
    private String type;
    private int energy;
    private Map<String, Object> metadata;

    public Spell(String name, String type, int energy, Map<String, Object> metadata) {
        this.name = name;
        this.type = type;
        this.energy = energy;
        this.metadata = metadata;
    }

    public String getName() { return name; }
    public String getType() { return type; }
    public int getEnergy() { return energy; }
    public Map<String, Object> getMetadata() { return metadata; }
} 