package sorceryscript.desktop;

import com.badlogic.gdx.backends.lwjgl3.Lwjgl3Application;
import com.badlogic.gdx.backends.lwjgl3.Lwjgl3ApplicationConfiguration;
import sorceryscript.SorceryScriptGame;

/**
 * Desktop launcher for SorceryScript.
 */
public class DesktopLauncher {
    public static void main (String[] arg) {
        Lwjgl3ApplicationConfiguration config = new Lwjgl3ApplicationConfiguration();
        config.setTitle("SorceryScript: The Magic Coding Language");
        config.setWindowedMode(1280, 800);
        config.useVsync(true);
        config.setResizable(true);
        new Lwjgl3Application(new SorceryScriptGame(), config);
    }
} 