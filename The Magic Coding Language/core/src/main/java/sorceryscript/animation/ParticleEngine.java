package sorceryscript.animation;

import com.badlogic.gdx.graphics.g2d.Batch;
import com.badlogic.gdx.scenes.scene2d.Actor;
import com.badlogic.gdx.scenes.scene2d.Group;
import com.badlogic.gdx.scenes.scene2d.Stage;

/**
 * ParticleEngine handles GPU-accelerated particle effects for spells.
 * Integrates with LibGDX for real-time rendering.
 */
public class ParticleEngine {
    private final Stage stage;
    private final Group particleGroup;

    public ParticleEngine(Stage stage) {
        this.stage = stage;
        this.particleGroup = new Group();
        stage.addActor(particleGroup);
    }

    /**
     * Triggers a particle effect for a given spell.
     * @param spellName The name of the spell
     * @param x X coordinate
     * @param y Y coordinate
     */
    public void triggerEffect(String spellName, float x, float y) {
        // TODO: Replace with real particle/shader effect
        particleGroup.addActor(new DemoParticleActor(spellName, x, y));
    }

    // Placeholder actor for demo particle effect
    private static class DemoParticleActor extends Actor {
        private final String spellName;
        public DemoParticleActor(String spellName, float x, float y) {
            this.spellName = spellName;
            setPosition(x, y);
            setSize(32, 32);
        }
        @Override
        public void draw(Batch batch, float parentAlpha) {
            // Draw a glowing circle or sprite for demo
            // TODO: Use real particle system or shader
        }
    }
} 