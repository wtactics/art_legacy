function stopUpload(){
	//show preview
	document.getElementById('preview-container').innerHTML = "<img id='preview' width='100%' src='" + window.frames['upload_target'].document.body.innerHTML + "'></img>";
	document.getElementById('preview-message').innerHTML = window.frames['upload_target'].document.body.innerHTML;
}

function saveUploadedImage(){
	//format the filename
	var filename = document.getElementById('preview').src;
	filename = escape("cards/temp/" + filename.substr(filename.lastIndexOf("/") + 1));

	//execute the save using PHP
	var ajaxRequest = new XMLHttpRequest();
	ajaxRequest.open("GET","index.php?act=save_upload&filename=" + filename + "&id=" + document.$_GET['id'] ,true);
	ajaxRequest.send();

	//reset some vars
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
			var path = ajaxRequest.responseText;
			//output the response
			document.getElementById('preview-message').innerHTML = path;

			//set the image field in the edit card form
			document.getElementById('image').value = path.substr(path.lastIndexOf(":") + 2);
		}
	}

}

function resetUploader(){
	document.getElementById('preview-action-container').innerHTML = '<input type="submit" name="submitBtn" value="Upload">';
	document.getElementById('preview-container').innerHTML = '';
}

(function(){ // Import GET Vars
   document.$_GET = [];
   var urlHalves = String(document.location).split('?');
   if(urlHalves[1]){
      var urlVars = urlHalves[1].split('&');
      for(var i=0; i<=(urlVars.length); i++){
         if(urlVars[i]){
            var urlVarPair = urlVars[i].split('=');
            document.$_GET[urlVarPair[0]] = urlVarPair[1];
         }
      }
   }
})();
