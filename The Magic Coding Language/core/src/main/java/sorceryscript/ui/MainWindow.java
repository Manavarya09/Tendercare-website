package sorceryscript.ui;

import com.badlogic.gdx.Gdx;
import com.badlogic.gdx.Screen;
import com.badlogic.gdx.graphics.GL20;
import com.badlogic.gdx.scenes.scene2d.Stage;
import com.badlogic.gdx.scenes.scene2d.ui.Skin;
import com.badlogic.gdx.utils.viewport.ScreenViewport;

import sorceryscript.animation.ParticleEngine;
import sorceryscript.dsl.SpellInterpreter;

/**
 * MainWindow is the main LibGDX screen for the SorceryScript UI.
 * Contains the code editor, battle arena, spellbook sidebar, etc.
 */
public class MainWindow implements Screen {
    private Stage stage;
    private Skin skin;
    private ParticleEngine particleEngine;
    private SpellInterpreter spellInterpreter;

    @Override
    public void show() {
        System.out.println("MainWindow show() called");
        stage = new Stage(new ScreenViewport());
        // TODO: Load a dark fantasy skin (use uiskin.json or custom)
        // skin = new Skin(Gdx.files.internal("styles/dark-fantasy.json"));
        particleEngine = new ParticleEngine(stage);
        spellInterpreter = new SpellInterpreter(particleEngine);
        // TODO: Add UI components: code editor, battle arena, sidebar, etc.
        Gdx.input.setInputProcessor(stage);
    }

    @Override
    public void render(float delta) {
        Gdx.gl.glClearColor(0.08f, 0.08f, 0.12f, 1);
        Gdx.gl.glClear(GL20.GL_COLOR_BUFFER_BIT);
        stage.act(delta);
        stage.draw();
    }

    @Override
    public void resize(int width, int height) {
        stage.getViewport().update(width, height, true);
    }
    @Override
    public void pause() {}
    @Override
    public void resume() {}
    @Override
    public void hide() {}
    @Override
    public void dispose() {
        stage.dispose();
        if (skin != null) skin.dispose();
    }
} 