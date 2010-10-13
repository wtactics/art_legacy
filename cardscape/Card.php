<?php

$sqlmappings = array(
	'[' => 'ENUM(',
	'{' => 'SET(',
	'I' => 'INT DEFAULT 0',
	'S' => 'VARCHAR(15)',
	'T' => 'VARCHAR(255)'
); ///< Mapping used with class FieldType to decide which member SQL type should be used for a member

/** Container class for a card's properties. It determines for each property if
  it is relevant for the card development area and/or the official cards area.
  And it determines what kind of SQL type should be used to store the card's
  information */
class FieldType {
	public $type; ///< SQL Datatype. Can be one of: STR, INT, TEXT, ENUM=["a","b",...], SET={"a","b",...}
	//public $priv; //user,mod,gamemaker,admin - creator has special priviledges in deving area for new cards
	public $areas; ///< In which areas is this property relevant?: none|both|dev|official

	/** Constructor
	  @param type encdeded SQL data type (see sqlmappings)
	  @param areas In which areas is this property relevant? */
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
); ///< The properties of a card in WTactics

/** Generate SQL code to create card tables. Cardscape uses two individual
  tables for the Card development Area and the Official Cards Area.
  This function generates the SQL code for both tables by looking at the
  cardfields array.
  @return A string with both table creation queries separated by a semicolon. */
function SQL_card_tables() {
	include 'config.php';
	$prefix = $conf[ 'Database' ][ 'prefix' ]; ///< Table prefix
	$dev_sql = '( id INT AUTO_INCREMENT PRIMARY KEY, ancestor INT, date DATE, author INT';
	$official_sql = 'CREATE TABLE '.$prefix.'cards '.$dev_sql;
	$dev_sql = 'CREATE TABLE '.$prefix.'dev '.$dev_sql;
	global $cardFields;
	global $sqlmappings;
	while( list( $name, $type ) = each( $cardFields ) ) {
		$append = ', '.$name.' ';
		if( isset( $sqlmappings[ $type -> type[ 0 ] ] ) ) { //array_key_exists( $type -> type[ 0 ], $sqlmappings ) ) {
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

/** The Card class. Its members should be identical to those in cardFields plus 
  id, ancestor, date and author. */
class Card {
	
	public $id; ///< Database key
	public $ancestor; ///< id of ancestor
	public $date; ///< last modified date
	public $author; ///< author's id

	public $name; ///< Official name of the card
	public $cost; ///< Cost in gold of the card
	public $threshold; ///< Threshold value of the card's faction
	public $faction; ///< The card's faction
	public $type; ///< The card's type (unit, spell,...)
	public $subtype; ///< The subtype of the card if existant
	public $rules; ///< The ruletex of the card
	public $flavor; ///< The informal flavor text
	public $image; ///< Image description, image URL or image reference
	public $attack; ///< Units only: The attack value
	public $defense; ///< Units only: The defense value
	public $status; ///< Card Development Area only: The card's development status
	public $revision; ///< Official Card Area only: Revision of this card

	/** Show card overview */
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

	/** Show edit form for card. Depending on the card status, not all fields will
	  be changeable. */
	function edit() {

	}

	/** Show admin's options */
	function admin() {

	}

}
?>
