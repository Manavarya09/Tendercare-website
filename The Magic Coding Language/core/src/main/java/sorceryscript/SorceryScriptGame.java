package sorceryscript;

import com.badlogic.gdx.Game;
import com.badlogic.gdx.graphics.g2d.SpriteBatch;

/**
 * Main entry point for SorceryScript game logic.
 * Initializes DSL interpreter, animation engine, multiplayer, and UI.
 */
public class SorceryScriptGame extends Game {
    public SpriteBatch batch;

    @Override
    public void create() {
        batch = new SpriteBatch();
        System.out.println("SorceryScriptGame started!");
        setScreen(new sorceryscript.ui.MainWindow());
        // TODO: Initialize DSL parser/interpreter
        // TODO: Initialize animation engine (particle effects, spell rendering)
        // TODO: Initialize multiplayer client/server
        // TODO: Initialize main UI (spell editor, battle arena, etc.)
    }

    @Override
    public void render() {
        super.render();
        // TODO: Main game loop, update and render all systems
    }

    @Override
    public void dispose() {
        batch.dispose();
        // TODO: Dispose of all resources
    }
} 