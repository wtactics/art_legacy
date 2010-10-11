<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<title></title>
		<!-- <link rel="stylesheet" href="" /> -->
		<script type="text/javascript">/* <![CDATA[ *//* ]]> */</script>
	</head>

	<body>
		<h1>Cardscape Installation</h1>
		<?php
		require_once 'config.php';
		require_once 'util.php';
		if( $conf[ 'accept' ] ) {
			$db = $conf[ 'Database' ];
			if( !mysql_connect( $db[ 'host' ], $db[ 'user' ], $db[ 'pass' ] ) ) {
				endhtml( 'Can not connect to database! Please adjust your database settings!' );
			}
			if( !mysql_query( 'USE '.$db[ 'database' ] ) ) {
				endhtml( 'Can not use database '.$db[ 'database' ]. '!' );
			}
			$old_installation = mysql_query( 'SHOW TABLES' );
			while( $table_name = mysql_fetch_row( $old_installation ) ) {
				if( strpos( $db[ 'prefix' ], $table_name[ 0 ] ) === 0 ) {
					endhtml( 'Cardscape is already installed! Remove old tables/database or choose another table prefix!' );
				}
			}

			$main_tables = file( 'cardscape.sql' );
			$query = '';
			foreach( $main_tables as $table ) {
				//echo '['.$table.']<br />';
				if( $table[ 0 ] !== "\t" ) {
					if( $query !== "" && !mysql_query( $query ) ) {
						endhtml( 'Query:' .$query. ' ---  Error: '.mysql_error() );
					}
					$query = 'CREATE TABLE '.$db[ 'prefix' ];
				}
				$query .= $table;
			}
			if( !mysql_query( $query ) ) {
				endhtml( 'Query:' .$query. ' ---  Error: '.mysql_error() );
			}

			include 'Card.php';
			$query = SQL_card_tables();
			if( mysql_query( $query ) ) {
				endhtml( 'Count not create card tables! Query used:<br />'.$query.'<br />Error:'.mysql_error() );
			}

			echo '<p>Congratulations! Cardscape has been installed successfully! You may now proceed to the <a href="index.php">start page</a></p>';


		} else {
			echo '<p>Cardscape is licensed under the <a href="http://www.gnu.org/licenses/agpl.html">GNU Afero General Public License 3</a> or any later version. You need to accept this license to use the software.</p>
				<form action="#" method="get"><div style="text-align:center">
				<textarea rows="30" cols="80" readonly="readonly">';

			include 'LICENSE';

			echo '
				</textarea></div></form>
				<p>In order to accept the license and to proceed with the installation, you need to set the <i>accept</i>-option in the config.php file to true. Additionally, you need to adjust the MySQL database configuration in that config file.</p>
				<p>If you are using a different card game than WTactics you need to replace the Card.php file with something that is adapted to your game.</p>
			       
				<p>After you are done, simply <a href="install.php">reload</a> this page.</p>';
		}
		?>
	</body>
</html>
