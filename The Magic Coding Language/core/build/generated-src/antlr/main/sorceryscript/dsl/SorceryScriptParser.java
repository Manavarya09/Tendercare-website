// Generated from SorceryScriptParser.g4 by ANTLR 4.13.1
package sorceryscript.dsl;
import org.antlr.v4.runtime.atn.*;
import org.antlr.v4.runtime.dfa.DFA;
import org.antlr.v4.runtime.*;
import org.antlr.v4.runtime.misc.*;
import org.antlr.v4.runtime.tree.*;
import java.util.List;
import java.util.Iterator;
import java.util.ArrayList;

@SuppressWarnings({"all", "warnings", "unchecked", "unused", "cast", "CheckReturnValue"})
public class SorceryScriptParser extends Parser {
	static { RuntimeMetaData.checkVersion("4.13.1", RuntimeMetaData.VERSION); }

	protected static final DFA[] _decisionToDFA;
	protected static final PredictionContextCache _sharedContextCache =
		new PredictionContextCache();
	public static final int
		CAST=1, SUMMON=2, FROM=3, WITH=4, USING=5, AT=6, LPAREN=7, RPAREN=8, COLON=9, 
		COMMA=10, SEMI=11, NUMBER=12, ID=13, WS=14;
	public static final int
		RULE_script = 0, RULE_statement = 1, RULE_castStmt = 2, RULE_summonStmt = 3, 
		RULE_coordList = 4, RULE_coord = 5;
	private static String[] makeRuleNames() {
		return new String[] {
			"script", "statement", "castStmt", "summonStmt", "coordList", "coord"
		};
	}
	public static final String[] ruleNames = makeRuleNames();

	private static String[] makeLiteralNames() {
		return new String[] {
			null, "'cast'", "'summon'", "'from'", "'with'", "'using'", "'at'", "'('", 
			"')'", "':'", "','", "';'"
		};
	}
	private static final String[] _LITERAL_NAMES = makeLiteralNames();
	private static String[] makeSymbolicNames() {
		return new String[] {
			null, "CAST", "SUMMON", "FROM", "WITH", "USING", "AT", "LPAREN", "RPAREN", 
			"COLON", "COMMA", "SEMI", "NUMBER", "ID", "WS"
		};
	}
	private static final String[] _SYMBOLIC_NAMES = makeSymbolicNames();
	public static final Vocabulary VOCABULARY = new VocabularyImpl(_LITERAL_NAMES, _SYMBOLIC_NAMES);

	/**
	 * @deprecated Use {@link #VOCABULARY} instead.
	 */
	@Deprecated
	public static final String[] tokenNames;
	static {
		tokenNames = new String[_SYMBOLIC_NAMES.length];
		for (int i = 0; i < tokenNames.length; i++) {
			tokenNames[i] = VOCABULARY.getLiteralName(i);
			if (tokenNames[i] == null) {
				tokenNames[i] = VOCABULARY.getSymbolicName(i);
			}

			if (tokenNames[i] == null) {
				tokenNames[i] = "<INVALID>";
			}
		}
	}

	@Override
	@Deprecated
	public String[] getTokenNames() {
		return tokenNames;
	}

	@Override

	public Vocabulary getVocabulary() {
		return VOCABULARY;
	}

	@Override
	public String getGrammarFileName() { return "SorceryScriptParser.g4"; }

	@Override
	public String[] getRuleNames() { return ruleNames; }

	@Override
	public String getSerializedATN() { return _serializedATN; }

	@Override
	public ATN getATN() { return _ATN; }

	public SorceryScriptParser(TokenStream input) {
		super(input);
		_interp = new ParserATNSimulator(this,_ATN,_decisionToDFA,_sharedContextCache);
	}

	@SuppressWarnings("CheckReturnValue")
	public static class ScriptContext extends ParserRuleContext {
		public List<StatementContext> statement() {
			return getRuleContexts(StatementContext.class);
		}
		public StatementContext statement(int i) {
			return getRuleContext(StatementContext.class,i);
		}
		public List<TerminalNode> SEMI() { return getTokens(SorceryScriptParser.SEMI); }
		public TerminalNode SEMI(int i) {
			return getToken(SorceryScriptParser.SEMI, i);
		}
		public ScriptContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_script; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof SorceryScriptParserListener ) ((SorceryScriptParserListener)listener).enterScript(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof SorceryScriptParserListener ) ((SorceryScriptParserListener)listener).exitScript(this);
		}
	}

	public final ScriptContext script() throws RecognitionException {
		ScriptContext _localctx = new ScriptContext(_ctx, getState());
		enterRule(_localctx, 0, RULE_script);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(17);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==CAST || _la==SUMMON) {
				{
				{
				setState(12);
				statement();
				setState(13);
				match(SEMI);
				}
				}
				setState(19);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	@SuppressWarnings("CheckReturnValue")
	public static class StatementContext extends ParserRuleContext {
		public CastStmtContext castStmt() {
			return getRuleContext(CastStmtContext.class,0);
		}
		public SummonStmtContext summonStmt() {
			return getRuleContext(SummonStmtContext.class,0);
		}
		public StatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_statement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof SorceryScriptParserListener ) ((SorceryScriptParserListener)listener).enterStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof SorceryScriptParserListener ) ((SorceryScriptParserListener)listener).exitStatement(this);
		}
	}

	public final StatementContext statement() throws RecognitionException {
		StatementContext _localctx = new StatementContext(_ctx, getState());
		enterRule(_localctx, 2, RULE_statement);
		try {
			setState(22);
			_errHandler.sync(this);
			switch (_input.LA(1)) {
			case CAST:
				enterOuterAlt(_localctx, 1);
				{
				setState(20);
				castStmt();
				}
				break;
			case SUMMON:
				enterOuterAlt(_localctx, 2);
				{
				setState(21);
				summonStmt();
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	@SuppressWarnings("CheckReturnValue")
	public static class CastStmtContext extends ParserRuleContext {
		public TerminalNode CAST() { return getToken(SorceryScriptParser.CAST, 0); }
		public List<TerminalNode> ID() { return getTokens(SorceryScriptParser.ID); }
		public TerminalNode ID(int i) {
			return getToken(SorceryScriptParser.ID, i);
		}
		public TerminalNode FROM() { return getToken(SorceryScriptParser.FROM, 0); }
		public TerminalNode WITH() { return getToken(SorceryScriptParser.WITH, 0); }
		public TerminalNode NUMBER() { return getToken(SorceryScriptParser.NUMBER, 0); }
		public CastStmtContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_castStmt; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof SorceryScriptParserListener ) ((SorceryScriptParserListener)listener).enterCastStmt(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof SorceryScriptParserListener ) ((SorceryScriptParserListener)listener).exitCastStmt(this);
		}
	}

	public final CastStmtContext castStmt() throws RecognitionException {
		CastStmtContext _localctx = new CastStmtContext(_ctx, getState());
		enterRule(_localctx, 4, RULE_castStmt);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(24);
			match(CAST);
			setState(25);
			match(ID);
			setState(26);
			match(FROM);
			setState(27);
			match(ID);
			setState(28);
			match(WITH);
			setState(29);
			match(NUMBER);
			setState(30);
			match(ID);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	@SuppressWarnings("CheckReturnValue")
	public static class SummonStmtContext extends ParserRuleContext {
		public TerminalNode SUMMON() { return getToken(SorceryScriptParser.SUMMON, 0); }
		public List<TerminalNode> ID() { return getTokens(SorceryScriptParser.ID); }
		public TerminalNode ID(int i) {
			return getToken(SorceryScriptParser.ID, i);
		}
		public TerminalNode AT() { return getToken(SorceryScriptParser.AT, 0); }
		public TerminalNode LPAREN() { return getToken(SorceryScriptParser.LPAREN, 0); }
		public CoordListContext coordList() {
			return getRuleContext(CoordListContext.class,0);
		}
		public TerminalNode RPAREN() { return getToken(SorceryScriptParser.RPAREN, 0); }
		public TerminalNode USING() { return getToken(SorceryScriptParser.USING, 0); }
		public SummonStmtContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_summonStmt; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof SorceryScriptParserListener ) ((SorceryScriptParserListener)listener).enterSummonStmt(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof SorceryScriptParserListener ) ((SorceryScriptParserListener)listener).exitSummonStmt(this);
		}
	}

	public final SummonStmtContext summonStmt() throws RecognitionException {
		SummonStmtContext _localctx = new SummonStmtContext(_ctx, getState());
		enterRule(_localctx, 6, RULE_summonStmt);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(32);
			match(SUMMON);
			setState(33);
			match(ID);
			setState(34);
			match(AT);
			setState(35);
			match(LPAREN);
			setState(36);
			coordList();
			setState(37);
			match(RPAREN);
			setState(38);
			match(USING);
			setState(39);
			match(ID);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	@SuppressWarnings("CheckReturnValue")
	public static class CoordListContext extends ParserRuleContext {
		public List<CoordContext> coord() {
			return getRuleContexts(CoordContext.class);
		}
		public CoordContext coord(int i) {
			return getRuleContext(CoordContext.class,i);
		}
		public List<TerminalNode> COMMA() { return getTokens(SorceryScriptParser.COMMA); }
		public TerminalNode COMMA(int i) {
			return getToken(SorceryScriptParser.COMMA, i);
		}
		public CoordListContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_coordList; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof SorceryScriptParserListener ) ((SorceryScriptParserListener)listener).enterCoordList(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof SorceryScriptParserListener ) ((SorceryScriptParserListener)listener).exitCoordList(this);
		}
	}

	public final CoordListContext coordList() throws RecognitionException {
		CoordListContext _localctx = new CoordListContext(_ctx, getState());
		enterRule(_localctx, 8, RULE_coordList);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(41);
			coord();
			setState(46);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==COMMA) {
				{
				{
				setState(42);
				match(COMMA);
				setState(43);
				coord();
				}
				}
				setState(48);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	@SuppressWarnings("CheckReturnValue")
	public static class CoordContext extends ParserRuleContext {
		public TerminalNode ID() { return getToken(SorceryScriptParser.ID, 0); }
		public TerminalNode COLON() { return getToken(SorceryScriptParser.COLON, 0); }
		public TerminalNode NUMBER() { return getToken(SorceryScriptParser.NUMBER, 0); }
		public CoordContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_coord; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof SorceryScriptParserListener ) ((SorceryScriptParserListener)listener).enterCoord(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof SorceryScriptParserListener ) ((SorceryScriptParserListener)listener).exitCoord(this);
		}
	}

	public final CoordContext coord() throws RecognitionException {
		CoordContext _localctx = new CoordContext(_ctx, getState());
		enterRule(_localctx, 10, RULE_coord);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(49);
			match(ID);
			setState(50);
			match(COLON);
			setState(51);
			match(NUMBER);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static final String _serializedATN =
		"\u0004\u0001\u000e6\u0002\u0000\u0007\u0000\u0002\u0001\u0007\u0001\u0002"+
		"\u0002\u0007\u0002\u0002\u0003\u0007\u0003\u0002\u0004\u0007\u0004\u0002"+
		"\u0005\u0007\u0005\u0001\u0000\u0001\u0000\u0001\u0000\u0005\u0000\u0010"+
		"\b\u0000\n\u0000\f\u0000\u0013\t\u0000\u0001\u0001\u0001\u0001\u0003\u0001"+
		"\u0017\b\u0001\u0001\u0002\u0001\u0002\u0001\u0002\u0001\u0002\u0001\u0002"+
		"\u0001\u0002\u0001\u0002\u0001\u0002\u0001\u0003\u0001\u0003\u0001\u0003"+
		"\u0001\u0003\u0001\u0003\u0001\u0003\u0001\u0003\u0001\u0003\u0001\u0003"+
		"\u0001\u0004\u0001\u0004\u0001\u0004\u0005\u0004-\b\u0004\n\u0004\f\u0004"+
		"0\t\u0004\u0001\u0005\u0001\u0005\u0001\u0005\u0001\u0005\u0001\u0005"+
		"\u0000\u0000\u0006\u0000\u0002\u0004\u0006\b\n\u0000\u00002\u0000\u0011"+
		"\u0001\u0000\u0000\u0000\u0002\u0016\u0001\u0000\u0000\u0000\u0004\u0018"+
		"\u0001\u0000\u0000\u0000\u0006 \u0001\u0000\u0000\u0000\b)\u0001\u0000"+
		"\u0000\u0000\n1\u0001\u0000\u0000\u0000\f\r\u0003\u0002\u0001\u0000\r"+
		"\u000e\u0005\u000b\u0000\u0000\u000e\u0010\u0001\u0000\u0000\u0000\u000f"+
		"\f\u0001\u0000\u0000\u0000\u0010\u0013\u0001\u0000\u0000\u0000\u0011\u000f"+
		"\u0001\u0000\u0000\u0000\u0011\u0012\u0001\u0000\u0000\u0000\u0012\u0001"+
		"\u0001\u0000\u0000\u0000\u0013\u0011\u0001\u0000\u0000\u0000\u0014\u0017"+
		"\u0003\u0004\u0002\u0000\u0015\u0017\u0003\u0006\u0003\u0000\u0016\u0014"+
		"\u0001\u0000\u0000\u0000\u0016\u0015\u0001\u0000\u0000\u0000\u0017\u0003"+
		"\u0001\u0000\u0000\u0000\u0018\u0019\u0005\u0001\u0000\u0000\u0019\u001a"+
		"\u0005\r\u0000\u0000\u001a\u001b\u0005\u0003\u0000\u0000\u001b\u001c\u0005"+
		"\r\u0000\u0000\u001c\u001d\u0005\u0004\u0000\u0000\u001d\u001e\u0005\f"+
		"\u0000\u0000\u001e\u001f\u0005\r\u0000\u0000\u001f\u0005\u0001\u0000\u0000"+
		"\u0000 !\u0005\u0002\u0000\u0000!\"\u0005\r\u0000\u0000\"#\u0005\u0006"+
		"\u0000\u0000#$\u0005\u0007\u0000\u0000$%\u0003\b\u0004\u0000%&\u0005\b"+
		"\u0000\u0000&\'\u0005\u0005\u0000\u0000\'(\u0005\r\u0000\u0000(\u0007"+
		"\u0001\u0000\u0000\u0000).\u0003\n\u0005\u0000*+\u0005\n\u0000\u0000+"+
		"-\u0003\n\u0005\u0000,*\u0001\u0000\u0000\u0000-0\u0001\u0000\u0000\u0000"+
		".,\u0001\u0000\u0000\u0000./\u0001\u0000\u0000\u0000/\t\u0001\u0000\u0000"+
		"\u00000.\u0001\u0000\u0000\u000012\u0005\r\u0000\u000023\u0005\t\u0000"+
		"\u000034\u0005\f\u0000\u00004\u000b\u0001\u0000\u0000\u0000\u0003\u0011"+
		"\u0016.";
	public static final ATN _ATN =
		new ATNDeserializer().deserialize(_serializedATN.toCharArray());
	static {
		_decisionToDFA = new DFA[_ATN.getNumberOfDecisions()];
		for (int i = 0; i < _ATN.getNumberOfDecisions(); i++) {
			_decisionToDFA[i] = new DFA(_ATN.getDecisionState(i), i);
		}
	}
}