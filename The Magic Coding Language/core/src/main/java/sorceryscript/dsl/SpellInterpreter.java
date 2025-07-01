package sorceryscript.dsl;

import org.antlr.v4.runtime.*;
import org.antlr.v4.runtime.tree.*;
import sorceryscript.animation.ParticleEngine;
// If you have not generated ANTLR sources, run the ANTLR tool to generate SorceryScriptLexer and SorceryScriptParser in sorceryscript.dsl
// import sorceryscript.dsl.SorceryScriptLexer;
// import sorceryscript.dsl.SorceryScriptParser;

/**
 * SpellInterpreter parses and executes SorceryScript DSL commands.
 * Integrates with the animation engine and game logic.
 */
public class SpellInterpreter {
    private final ParticleEngine particleEngine;

    public SpellInterpreter(ParticleEngine particleEngine) {
        this.particleEngine = particleEngine;
    }

    /**
     * Parses and executes a spell script.
     * @param script The SorceryScript DSL code
     */
    public void interpret(String script) {
        CharStream input = CharStreams.fromString(script);
        SorceryScriptLexer lexer = new SorceryScriptLexer(input);
        CommonTokenStream tokens = new CommonTokenStream(lexer);
        SorceryScriptParser parser = new SorceryScriptParser(tokens);
        ParseTree tree = parser.script();
        walk(tree);
    }

    private void walk(ParseTree tree) {
        // Walk the parse tree and trigger spell effects
        for (int i = 0; i < tree.getChildCount(); i++) {
            ParseTree stmt = tree.getChild(i);
            if (stmt instanceof SorceryScriptParser.StatementContext) {
                ParseTree child = stmt.getChild(0);
                if (child instanceof SorceryScriptParser.CastStmtContext) {
                    handleCast((SorceryScriptParser.CastStmtContext) child);
                } else if (child instanceof SorceryScriptParser.SummonStmtContext) {
                    handleSummon((SorceryScriptParser.SummonStmtContext) child);
                }
            }
        }
    }

    private void handleCast(SorceryScriptParser.CastStmtContext ctx) {
        String spellName = ctx.ID(0).getText();
        String spellType = ctx.ID(1).getText();
        int energy = Integer.parseInt(ctx.NUMBER().getText());
        // For demo: trigger effect at center
        particleEngine.triggerEffect(spellName, 640, 400);
        System.out.println("Cast spell: " + spellName + " of type " + spellType + " with " + energy + " energy");
    }

    private void handleSummon(SorceryScriptParser.SummonStmtContext ctx) {
        String entity = ctx.ID(0).getText();
        String token = ctx.ID(1).getText();
        // For demo: trigger effect at (x, y) from coordList
        int x = 0, y = 0;
        for (SorceryScriptParser.CoordContext coord : ctx.coordList().coord()) {
            if (coord.ID().getText().equals("x")) x = Integer.parseInt(coord.NUMBER().getText());
            if (coord.ID().getText().equals("y")) y = Integer.parseInt(coord.NUMBER().getText());
        }
        particleEngine.triggerEffect(entity, x, y);
        System.out.println("Summon entity: " + entity + " at (" + x + ", " + y + ") using " + token);
    }
} 