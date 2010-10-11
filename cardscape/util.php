<?php
function inputField( $name, $explanation, $type = 'text', $value = '' ) {
	echo '<input type="'.$type.'" name="'.$name.'" id="'.$name.'" value="'.$value.'" />
		<label for="'.$name.'">'.$explanation.'</label><br />';
}

function selectField( $name, $explanation, $options ) {
	echo '<select size="'.count( $options ).'" name="'.$name.'" id="'.$name.'" multiple="multiple">';
	foreach( $options as $option ) {
		echo '<option value="'.$option.'">'.$option.'</option>';
	}
	echo '</select><label for="'.$name.'">'.$explanation.'</label><br />';
}

function submitButton( $name, $value ) {
	echo '<input type="submit" value="'.$value.'" name="'.$name.'" id="'.$name.'" /><br />';
}

function textarea( $name, $explanation, $rows = 2 ) {
	echo '<label for="'.$name.'">'.$explanation.':</label><br />
		<textarea rows="'.$rows.'" cols="50" name="'.$name.'" id="'.$name.'"></textarea><br />';
}

function msg( $text, $class='informative' ) {
	echo '<p class="'.$class.'">'.htmlentities( $text ).'</p>';
}

function endhtml( $lastwords = "", $type='error' ) {
	msg( $lastwords, $type );
	die( '</body></html>' );
}

function tablerow( $name, $value ) {
	echo '<tr>';
	if( is_array( $value ) ) {
		echo '<td>'.$name.'</td><td><ul>';
		foreach( $value as $val ) {
			echo '<li>'.$val.'</li>';
		}
		echo '</ul>';
	} else {
		echo '<td>'.$name.'<td><td>'.$value;
	}
	echo '</td></tr>';
}

?>
