package sorceryscript.multiplayer;

import java.io.IOException;
import java.net.ServerSocket;

/**
 * Server handles multiplayer connections for turn-based magic duels.
 * Uses Java NIO/TCP for networking.
 */
public class Server {
    private ServerSocket serverSocket;

    public Server(int port) throws IOException {
        serverSocket = new ServerSocket(port);
        // TODO: Accept client connections and manage game state
    }

    public void start() {
        // TODO: Main server loop
    }

    public void stop() throws IOException {
        serverSocket.close();
    }
} 