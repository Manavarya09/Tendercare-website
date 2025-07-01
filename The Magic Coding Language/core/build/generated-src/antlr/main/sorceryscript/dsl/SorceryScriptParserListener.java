// Generated from SorceryScriptParser.g4 by ANTLR 4.13.1
package sorceryscript.dsl;
import org.antlr.v4.runtime.tree.ParseTreeListener;

/**
 * This interface defines a complete listener for a parse tree produced by
 * {@link SorceryScriptParser}.
 */
public interface SorceryScriptParserListener extends ParseTreeListener {
	/**
	 * Enter a parse tree produced by {@link SorceryScriptParser#script}.
	 * @param ctx the parse tree
	 */
	void enterScript(SorceryScriptParser.ScriptContext ctx);
	/**
	 * Exit a parse tree produced by {@link SorceryScriptParser#script}.
	 * @param ctx the parse tree
	 */
	void exitScript(SorceryScriptParser.ScriptContext ctx);
	/**
	 * Enter a parse tree produced by {@link SorceryScriptParser#statement}.
	 * @param ctx the parse tree
	 */
	void enterStatement(SorceryScriptParser.StatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link SorceryScriptParser#statement}.
	 * @param ctx the parse tree
	 */
	void exitStatement(SorceryScriptParser.StatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link SorceryScriptParser#castStmt}.
	 * @param ctx the parse tree
	 */
	void enterCastStmt(SorceryScriptParser.CastStmtContext ctx);
	/**
	 * Exit a parse tree produced by {@link SorceryScriptParser#castStmt}.
	 * @param ctx the parse tree
	 */
	void exitCastStmt(SorceryScriptParser.CastStmtContext ctx);
	/**
	 * Enter a parse tree produced by {@link SorceryScriptParser#summonStmt}.
	 * @param ctx the parse tree
	 */
	void enterSummonStmt(SorceryScriptParser.SummonStmtContext ctx);
	/**
	 * Exit a parse tree produced by {@link SorceryScriptParser#summonStmt}.
	 * @param ctx the parse tree
	 */
	void exitSummonStmt(SorceryScriptParser.SummonStmtContext ctx);
	/**
	 * Enter a parse tree produced by {@link SorceryScriptParser#coordList}.
	 * @param ctx the parse tree
	 */
	void enterCoordList(SorceryScriptParser.CoordListContext ctx);
	/**
	 * Exit a parse tree produced by {@link SorceryScriptParser#coordList}.
	 * @param ctx the parse tree
	 */
	void exitCoordList(SorceryScriptParser.CoordListContext ctx);
	/**
	 * Enter a parse tree produced by {@link SorceryScriptParser#coord}.
	 * @param ctx the parse tree
	 */
	void enterCoord(SorceryScriptParser.CoordContext ctx);
	/**
	 * Exit a parse tree produced by {@link SorceryScriptParser#coord}.
	 * @param ctx the parse tree
	 */
	void exitCoord(SorceryScriptParser.CoordContext ctx);
}