parser grammar SorceryScriptParser;

options { tokenVocab=SorceryScriptLexer; }

script      : (statement SEMI)* ;

statement   : castStmt
            | summonStmt
            ;

castStmt    : CAST ID FROM ID WITH NUMBER ID ;
summonStmt  : SUMMON ID AT LPAREN coordList RPAREN USING ID ;

coordList   : coord (COMMA coord)* ;
coord       : ID COLON NUMBER ; 