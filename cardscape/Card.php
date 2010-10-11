<?php
$sqlmappings = array(
	'[' => 'ENUM(',
	'{' => 'SET(',
	'I' => 'INT DEFAULT 0',
	'S' => 'VARCHAR(15)',
	'T' => 'VARCHAR(255)'
);

class FieldType {
	public $type; //STR, INT, TEXT, ENUM=["a","b",...], SET={"a","b",...}
	//public $priv; //user,mod,gamemaker,admin - creator has special priviledges in deving area for new cards
	public $areas; //none|both|dev|official

	function __construct( $type, $areas = "both" ) {
		$this-> type = $type;
		$this-> areas = $areas;
	}
}

$cardFields = array( //predefined fields: id, ancestor, date, author
	'name' => new FieldType( 'STR' ),
	'cost' => new FieldType( 'INT' ),
	'threshold' => new FieldType( 'INT' ),
	'faction' => new FieldType( '[Merfolk,Rebels,Order of Dawn' ),
	'type' => new FieldType( '[unit,magic,ritual' ),
	'subtype' => new FieldType( 'STR' ),
	'rules' => new FieldType( 'TEXT' ),
	'flavor' => new FieldType( 'TEXT' ),
	'image' => new FieldType( 'TEXT' ),
	'attack' => new FieldType( 'INT' ),
	'defense' => new FieldType( 'INT' ),

	'status' => new FieldType( '[concept,new,discussed,playtested,official,rejected,superseded', 'dev' ),
	'revision' => new FieldType( 'INT', 'official' )
);

function SQL_card_tables() {
	include 'config.php';
	$prefix = $conf[ 'Database' ][ 'prefix' ];
	$dev_sql = '( id INT AUTO_INCREMENT PRIMARY KEY, ancestor INT, date DATE, author INT';
	$official_sql = 'CREATE TABLE '.$prefix.'cards '.$dev_sql;
	$dev_sql = 'CREATE TABLE '.$prefix.'dev '.$dev_sql;
	global $cardFields;
	global $sqlmappings;
	while( list( $name, $type ) = each( $cardFields ) ) {
		$append = ', '.$name.' ';
		if( array_key_exists( $type -> type[ 0 ], $sqlmappings ) ) {
			$append .= $sqlmappings[ $type -> type[ 0 ] ];
			//if the type is a set or a list:
			if( $type -> type[ 0 ] == '[' || $type -> type[ 0 ] == '{' ) {
				$append .= '"'. preg_replace( '/,/', '","', substr( $type -> type, 1 ) ) .'")';
			}
		}
		if( $type -> areas == 'both' ) {
			$dev_sql .= $append;
			$official_sql .= $append;
		} elseif( $type -> areas == 'dev' ) {
			$dev_sql .= $append;
		} elseif( $type -> areas == 'official' ) {
			$official_sql .= $append;
		}
	}
	return $dev_sql.');'.$official_sql.');';
}

class Card {
	
	public $name;
	public $cost;
	public $threshold;
	public $faction;
	public $type;
	public $subtype;
	public $rules;
	public $flavor;
	public $image;
	public $attack;
	public $defense;
	public $status;
	public $revision;

	public function show() {
		echo '<div class="card_display"><table>';
		tablerow( 'Card name', '<a href="index.php?showcard='.$this->id.'">'.$this->name.'</a>' );
		tablerow( 'Gold cost', $this->cost );
		tablerow( 'Threshold', $this->threshold );
		tablerow( 'Factions', $this->factions );
		tablerow( 'Type', $this->type );
		tablerow( 'Subtype', $this->subtype );
		tablerow( 'Ruletext', $this->rules );
		tablerow( 'Flavortext', '<i>'.$this->flavor.'</i>' );
		if( $this->type == 'unit' ) {
			tablerow( 'Attack', $this->attack );
			tablerow( 'Defense', $this->defense );
		}
		tablerow( '', '' );
		$ancestor = mysql_query( 'SELECT name FROM cards WHERE id='.$this->ancestor.' LIMIT 1' );
		if( mysql_num_rows( $ancestor ) ) {
			$ancestorName = array_pop( mysql_fetch_row( $ancestor ) );
			tablerow( 'Card predecessor', '<a href="index.php?showcard='.$this->ancestor.'">'.$ancestorName.'</a>' );
		}

		$successors = mysql_query( 'SELECT id, name, status FROM cards WHERE ancestor='.$this->id );
		while( $successor = mysql_fetch_array( $successors ) ) {
			tablerow( 'Successor', '<a href="index.php?showcard='.$successor[ 'id' ].'" class="successor_'.$successor[ 'status' ].'">
				'.$successor[ 'name' ].'</a>' );
		}
		echo '</table><p><a href="index.php?editcard='.$this->id.'">Edit this card?</a></p>';
		$comments = mysql_query( 'SELECT u.name, c.text FROM comments c RIGHT JOIN users u ON c.user=u.uid' ); //TODO date
		while( $comment = mysql_fetch_row( $comments ) ) {
			echo '<div class="comment_header">'.$comment[ 0 ].' says:</div>
				<div class="comment">'.$comment[ 1 ].'</div>';
		}
		
		echo '<form method="post" action="index?comment_card='.$this->id.'">
			<fieldset><legend>Comment on card</legend>';
		textarea( 'comment', 'Your comment', 5 );
		submitButton( 'newcomment', 'comment' );

		echo '</div>';
	}

	function edit() {

	}

	function admin() {

	}

}
?>
