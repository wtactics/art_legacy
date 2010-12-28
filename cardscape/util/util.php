<?php
/**
@file util.php

Various utility functions that help the lazy programmer to save some keystrokes
*/

/** Create an input field
  @param name Name and ID value
  @param explanation Label value
  @param type Like in HTML the type of an &lt;input&gt;
  @param value The value of the input */
function inputField( $name, $explanation, $type = 'text', $value = '' ) {
	echo '<input type="'.$type.'" name="'.$name.'" id="'.$name.'" value="'.$value.'" />';
	if( $type != 'hidden' ) {
		echo '<label for="'.$name.'">'.$explanation.'</label><br />';
	}
}

/** Create a select field. All options will be shown directly. Now popup-menu is used.
  @param name Name and ID value
  @param explanation Label value
  @param options Array: The different options */
function selectField( $name, $explanation, $options ) {
	echo '<select size="'.count( $options ).'" name="'.$name.'" id="'.$name.'" multiple="multiple">';
	foreach( $options as $option ) {
		echo '<option value="'.$option.'">'.$option.'</option>';
	}
	echo '</select><label for="'.$name.'">'.$explanation.'</label><br />';
}

/** Create a submit button.
  @param name Name and ID value
  @param value The button's text */ 
function submitButton( $name, $value ) {
	echo '<input type="submit" value="'.$value.'" name="'.$name.'" id="'.$name.'" /><br />';
}

/** Create an emty textarea.
  @param name Name and ID of the textarea
  @param explanation Label value
  @param rows Number of rows to show */
function textarea( $name, $default, $rows = 2 ) {
	return "<textarea rows='$rows' cols='50' name='$name'>$default</textarea>";
}

/** Show a message to the user.
  @param text Text of the message
  @param class CSS class of the message */
function msg( $text, $class='informative' ) {
	echo '<p class="'.$class.'">'.htmlentities( $text ).'</p>';
}

/** Show an error message to the user and stop processing of the page.
  @param lastwords The error message
  @param type CSS class of the message */
function endhtml( $lastwords = "", $type='error' ) {
	msg( $lastwords, $type );
	die( '</body></html>' );
}

/** Display a table's row
   @param name The left field's text
   @param value Array: A list shown in the right field. */
function tablerow( $name, $value ) {
	echo '<tr>';
	if( is_array( $value ) ) {
		echo '<td>'.$name.'</td><td><ul>';
		foreach( $value as $val ) {
			echo '<li>'.$val.'</li>';
		}
		echo '</ul>';
	} else {
		echo '<td>'.$name.'</td><td>'.$value;
	}
	echo "</td></tr>\n";
}

/** Takes an enum string passed to it from the card definition and parses it into a select box
   @param enum The enum string. [eg. "Title ENUM( 'Option 1', 'Option 2', 'Option 3' ) DEFAULT 'Option 1'"]*/
function enum_array($enum)
{
	$list = null;
	if(preg_match_all("/(.|DEFAULT.)'[^,)]+'/", $enum, $matches)){
		foreach( $matches[0] as $l ){
			$l = str_replace("'","",trim($l));
			if(!preg_match("/^DEFAULT /",$l)){
				$list["$l"] = $l;}
		}
		return $list;
	}
	return false;
}

/** Echoes a dropdown link box
   @param title The title of the dropdown link box as a string.
   @param links An array of strings containing the code for each link.*/
function dropdown_links($title, $links, $width){
	$id = strtolower(str_replace(" ", "", $title));
	echo "<dl class='dropdown' style='margin:0; width:$width'>\n";
	echo "<dt style='margin:0; width:$width; padding: 0px; text-align:center' id='$id-ddheader' onmouseover=\"ddMenu('$id',1)\" onmouseout=\"ddMenu('$id',-1)\">$title</dt>";
	echo "<dd style='width:$width;' id='$id-ddcontent' onmouseover=\"cancelHide('$id')\" onmouseout=\"ddMenu('$id',-1)\">";
	echo "<ul style='width:$width;'>";
	foreach($links as $l){
		echo "<li class='underline'>$l</li>\n";}
	//echo "<li><a href='http://chaosrealm.net/wtactics/wiki' class='underline'>Wiki</a></li>";
	//echo "<li><a href='http://chaosrealm.net/wtactics'>Blog</a></li>";
	echo "</ul>\n</dd>\n</dl>\n";

}
?>
