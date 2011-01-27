<?
/* TAKES A FILE FROM THE FORM AND PUTS IT IN ./cards/temp/ */
function upload_image($id){
	if ($_FILES["file"]["error"] > 0){
		echo "Error: " . $_FILES["file"]["error"] . "<br>";}
	else{
		/*
		//DEBUG STUFF
		echo "Upload: " . $_FILES["file"]["name"] . "<br>";
		echo "Type: " . $_FILES["file"]["type"] . "<br>";
		echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br>";
		echo "Stored in: " . $_FILES["file"]["tmp_name"];
		*/
		
		if($_FILES["file"]["type"] <> "image/png"){
			die("Error! Only .png files can be uploaded.");}
		
		$key = rand(); //this makes it so the temporary filename is random
		move_uploaded_file($_FILES["file"]["tmp_name"],"cards/temp/$key.png") or die("Error! " . $_FILES["file"]["error"]);
		echo "cards/temp/$key.png";
	}
}

/* MOVES THE TEMPORARY FILE TO THE APPROPRIATE PLACE */
function save_image($id, $filename){
	$imageversion = get_next_open_image_version($id);
	rename($filename, "cards/$id-$imageversion.png") or die("Error moving file. " . "$filename > cards/$id-$imageversion.png");
	echo "Image saved to: cards/$id-$imageversion.png";
}

/* RETURNS THE FIRST AVAILABLE IMAGE VERSION NUMBER FOR THE CARD OF ID */
function get_next_open_image_version($id){
	$version = 1;
	while(file_exists("cards/$id-$version.png")){
		$version++;}
	return $version;
}
?>
