package sorceryscript.multiplayer;

import java.io.IOException;
import java.net.Socket;

/**
 * Client connects to the multiplayer server for magic duels.
 * Uses Java NIO/TCP for networking.
 */
public class Client {
    private Socket socket;

    public Client(String host, int port) throws IOException {
        socket = new Socket(host, port);
        // TODO: Communicate with server and send/receive spell actions
    }

    public void disconnect() throws IOException {
        socket.close();
    }
} 