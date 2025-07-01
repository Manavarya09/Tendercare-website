

# SorceryScript: The Magic Coding Language

SorceryScript is a AAA-quality, fantasy-industrial desktop application and game where you write and cast magical spells as code! Featuring a custom domain-specific language (DSL), real-time spell animations, multiplayer duels, and a beautiful dark fantasy UI, SorceryScript is both a creative tool and a competitive game.

## Features

- **Custom DSL**: Write spells like `cast ignite from fire with 3 energy;` or `summon phoenix at (x: 200, y: 150) using flameToken;` using a powerful, extensible language.
- **Handwritten/ANTLR Parser**: Robust parsing and interpretation of magical commands.
- **Real-Time Spell Animations**: GPU-accelerated particle effects and cinematic magic using LibGDX.
- **Multiplayer Duels**: Socket-based server-client system for turn-based magic battles and chat.
- **Modular Scripting**: Import/export user-created spells as plugins (JSON or script files).
- **AAA Fantasy UI**: Modern, dark-themed, animated UI inspired by Diablo/Skyrim spellbooks.
- **Code Editor**: Syntax highlighting, drag-and-drop spell creation, and live battle visuals.
- **Spellbook Library**: Filter, search, and sort spells by type (Fire, Water, Necromancy, etc.).
- **AI Assistant (Bonus)**: Suggests new spells based on your code (OpenAI/local LLM integration).
- **Database**: Spell metadata stored in JSON or embedded SQLite.

## Tech Stack

- Java 17+
- LibGDX (2D/3D rendering, cross-platform)
- ANTLR (DSL parsing)
- Java NIO/TCP (multiplayer)
- Gradle (build system)
- Optional: SQLite, OpenAI API

## Project Structure

```
SorceryScript/
├── README.md
├── build.gradle
├── settings.gradle
├── core/                # Main game logic, DSL, animation, multiplayer, etc.
├── desktop/             # Desktop launcher
├── assets/              # Shared assets (spells, shaders, images, etc.)
```

## Getting Started

1. **Clone the repo:**
   ```
   git clone https://github.com/Manavarya09/SoceryScript.git
   cd SoceryScript
   ```
2. **Build and run (Desktop):**
   ```
   ./gradlew desktop:run
   ```
3. **Explore:**
   - Write spells in the code editor
   - Duel friends in multiplayer
   - Create/import/export custom spells

## Contributing
Pull requests and suggestions are welcome! See the [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License
MIT License. See [LICENSE](LICENSE) for details.

---
