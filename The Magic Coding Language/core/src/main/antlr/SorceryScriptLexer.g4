lexer grammar SorceryScriptLexer;

CAST        : 'cast';
SUMMON      : 'summon';
FROM        : 'from';
WITH        : 'with';
USING       : 'using';
AT          : 'at';
LPAREN      : '(';
RPAREN      : ')';
COLON       : ':';
COMMA       : ',';
SEMI        : ';';
NUMBER      : [0-9]+;
ID          : [a-zA-Z_][a-zA-Z0-9_]*;
WS          : [ \t\r\n]+ -> skip; 