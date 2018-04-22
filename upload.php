<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	include('includes/header.php');
	// CREATE VARIABLES
	$profile_id = $user['username'];
	$imgSrc = "";
	$result_path = "";
	$msg = "";

	/***********************************************************
		0 - REMOVE THE TEMP IMAGE IF IT EXISTS
	***********************************************************/
	if (!isset($_POST['x']) && !isset($_FILES['image']['name']) ){
		// DELECT USERS TEMP IMAGE
		$temppath = 'assets/img/profile_pics/'.$profile_id.'_temp.jpeg';
		if (file_exists ($temppath)){ @unlink($temppath); }
	} 

	if(isset($_FILES['image']['name'])){	
	/***********************************************************
		1 - UPLOAD ORIGINAL IMAGE TO SERVER
	***********************************************************/	
		// GET NAME | SIZE | TEMP LOCATION
		$ImageName = $_FILES['image']['name'];
		$ImageSize = $_FILES['image']['size'];
		$ImageTempName = $_FILES['image']['tmp_name'];
		// GET FILE EXTENSTION
		$ImageType = @explode('/', $_FILES['image']['type']);
		$type = $ImageType[1]; // FILE TYPE	
		// SET UPLOAD DIRECTORY
		$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/Echo-Chamber/assets/img/profile_pics';
		// SET FILE NAME
		$file_temp_name = $profile_id.'_original.'.md5(time()).'n'.$type; // TEMP FILE NAME
		$fullpath = $uploaddir."/".$file_temp_name; // TEMP FILE PATH
		// $PROFILE_ID.'_TEMP.'.$TYPE; // FOR THE FINAL RESIZED IMAGE
		$file_name = $profile_id.'_temp.jpeg'; 
		$fullpath_2 = $uploaddir."/".$file_name; // FOR THE FINAL RESIZED IMAGE
		// MOVE THE FILE TO CORRECT LOCATION
		$move = move_uploaded_file($ImageTempName ,$fullpath) ; 
		chmod($fullpath, 0777);  
		// CHECK FOR VALID UPLOAD
		if (!$move) { 
			die ('File didnt upload');
		} else { 
			$imgSrc= "assets/img/profile_pics/".$file_name; // THE IMAGE TO DISPLAY IN CROP AREA
			$msg= "Upload Complete!"; // MESSAGE TO PAGE
			$src = $file_name; // THE FILE NAME TO POST FROM CROPPING FORM TO THE RESIZE
		} 

	/***********************************************************
		2  - RESIZE THE IMAGE TO FIT IN CROPPING AREA
	***********************************************************/		
		// GET THE UPLOADED IMAGE SIZE
		clearstatcache();				
		$original_size = getimagesize($fullpath);
		$original_width = $original_size[0];
		$original_height = $original_size[1];	
		// SPECIFY THE NEW SIZE
		$main_width = 500; // SET THE WIDTH OF THE IMAGE
		$main_height = $original_height / ($original_width / $main_width); // SET HEIGHT IN RATIO
		// CREATE NEW IMAGE USING CORRECT PHP FUNCTION
		if($_FILES["image"]["type"] == "image/gif"){
			$src2 = imagecreatefromgif($fullpath);
		}elseif($_FILES["image"]["type"] == "image/jpeg" || $_FILES["image"]["type"] == "image/pjpeg"){
			$src2 = imagecreatefromjpeg($fullpath);
		}elseif($_FILES["image"]["type"] == "image/png"){ 
			$src2 = imagecreatefrompng($fullpath);
		}else{ 
			$msg .= "There was an error uploading the file. Please upload a .jpg, .gif or .png file. <br />";
		}
		// CREATE THE NEW RESIZED IMAGE
		$main = imagecreatetruecolor($main_width,$main_height);
		imagecopyresampled($main,$src2,0, 0, 0, 0,$main_width,$main_height,$original_width,$original_height);
		// UPLOAD NEW VERSION
		$main_temp = $fullpath_2;
		imagejpeg($main, $main_temp, 90);
		chmod($main_temp,0777);
		// FREE UP MEMORY
		imagedestroy($src2);
		imagedestroy($main);
		//imagedestroy($fullpath);
		@ unlink($fullpath); // DELETE THE ORIGINAL UPLOAD
										
	}//ADD Image 	

	/***********************************************************
		3- CROPPING & CONVERTING THE IMAGE TO JPG
	***********************************************************/
	if (isset($_POST['x'])) {
		// THE FILE TYPE POSTED
		$type = $_POST['type'];	
		// THE IMAGE SRC
		$src = 'assets/img/profile_pics/'.$_POST['src'];	
		$finalname = $profile_id.md5(time());	
		
		if($type == 'jpg' || $type == 'jpeg' || $type == 'JPG' || $type == 'JPEG'){	
			// THE TARGET DIMENSIONS 150X150
			$targ_w = $targ_h = 150;
			// QUALITY OF THE OUTPUT
			$jpeg_quality = 90;
			// CREATE A CROPPED COPY OF THE IMAGE
			$img_r = imagecreatefromjpeg($src);
			$dst_r = imagecreatetruecolor( $targ_w, $targ_h );
			imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
			$targ_w,$targ_h,$_POST['w'],$_POST['h']);
			// SAVE THE NEW CROPPED VERSION
			imagejpeg($dst_r, "assets/img/profile_pics/".$finalname."n.jpeg", 90); 	
				 		
		} else if ($type == 'png' || $type == 'PNG') {
			// THE TARGET DIMENSIONS 150X150
			$targ_w = $targ_h = 150;
			// QUALITY OF THE OUTPUT
			$jpeg_quality = 90;
			// CREATE A CROPPED COPY OF THE IMAGE
			$img_r = imagecreatefrompng($src);
			$dst_r = imagecreatetruecolor( $targ_w, $targ_h );		
			imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
			$targ_w,$targ_h,$_POST['w'],$_POST['h']);
			// SAVE THE NEW CROPPED VERSION
			imagejpeg($dst_r, "assets/img/profile_pics/".$finalname."n.jpeg", 90); 	
							
		} else if ($type == 'gif' || $type == 'GIF') {
			// THE TARGET DIMENSIONS 150X150
			$targ_w = $targ_h = 150;
			// QUALITY OF THE OUTPUT
			$jpeg_quality = 90;
			// CREATE A CROPPED COPY OF THE IMAGE
			$img_r = imagecreatefromgif($src);
			$dst_r = imagecreatetruecolor( $targ_w, $targ_h );		
			imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
			$targ_w,$targ_h,$_POST['w'],$_POST['h']);
			// SAVE THE NEW CROPPED VERSION
			imagejpeg($dst_r, "assets/img/profile_pics/".$finalname."n.jpeg", 90); 	
		}
			// FREE UP MEMORY
			imagedestroy($img_r); // FREE UP MEMORY
			imagedestroy($dst_r); // FREE UP MEMORY
			@ unlink($src); // DELETE THE ORIGINAL UPLOAD					
			
			// RETURN CROPPED IMAGE TO PAGE
			$result_path ="assets/img/profile_pics/".$finalname."n.jpeg";

			// INSERT IMAGE INTO DATABASE
			$insert_pic_query = mysqli_query($connection, "UPDATE users SET profile_pic='$result_path' WHERE username='$userLoggedIn'");
			header("Location: ".$userLoggedIn);
															
	}// END IF
	?>

	<div id="Overlay" style=" width:100%; height:100%; border:0px #990000 solid; position:absolute; top:0px; left:0px; z-index:2000; display:none;"></div>

	<!-- UPLOAD NEW PROFILE PIC PANEL -->
	<div class="column col-xl-10 col-lg-12 col-md-12" id="upload-pic-panel">
		<!-- UPLOAD NEW PROFILE PIC TITLE -->
		<div class="col-md-12 text-center">
			<h2>Choose New Profile Picture</h2>
		</div>
		<hr />
		<br />
		<!-- UPLOAD INSTRUCTIONS AND FORM -->
		<div id="formExample">
			<!-- INSTRUCTIONS -->
			<p>Upload a new profile picture for your profile.</p>
			<p>First click the "Add Image" button and choose a file from your computer.</p>
			<p>After you have selected an image, hitting the "Submit" button will allow you the crop the image before setting it as to your new profile picture.</p>
			<br />
	    <p><b> <?=$msg?> </b></p>
			<!-- UPLOAD NEW PIC FORM -->
	    <form action="upload.php" method="post" enctype="multipart/form-data">
	    	<!-- CHOOSE IMAGE BUTTON -->
        <input type="file" id="image" name="image" class="inputfile" data-multiple-caption="{count} files selected" multiple />
        <label id="uploadImageTwo" for="image" class="text-center">
					<img src="assets/img/icons/image-icon-white.png">
					Choose a file...
					<small><span style="color: #F7F8F9;"></span></small>
				</label>
        <br /><br />
        <!-- CROP AND SUBMIT BUTTON -->
        <input class="btn btn-success" type="submit" value="Submit" />
        <br /><br />
	    </form>
		  <!-- END UPLOAD NEW PIC FORM -->
		</div>
		<!-- END UPLOAD INSTRUCTIONS AND FORM -->

	    <?php
	    // IF AN IMAGE HAD BEEN UPLOADED DISPLAY CROPPING AREA
	    if ($imgSrc) { ?>
		    <script>
		    	$('#Overlay').show();
				$('#formExample').hide();
		    </script>

				<!-- CROPPING CONTAINER DIV -->
		    <div id="CroppingContainer">  
		    	<!-- CROPPING AREA -->
	        <div id="CroppingArea">	
            <img src="<?=$imgSrc?>" border="0" id="jcrop_target" />
	        </div>  
					<!-- MAKE PROFILE PIC BUTTON -->
	        <div id="CropImageForm" class="text-center">  
            <form action="upload.php" method="post" onsubmit="return checkCoords();">
              <input type="hidden" id="x" name="x" />
              <input type="hidden" id="y" name="y" />
              <input type="hidden" id="w" name="w" />
              <input type="hidden" id="h" name="h" />
              <input type="hidden" value="jpeg" name="type" /> <?php // $type ?> 
              <input type="hidden" value="<?=$src?>" name="src" />
              <input type="submit" class="btn btn-success" value="Make Profile Pic" />
            </form>
	        </div>
					<!-- CANCEL BUTTON -->
	        <div id="CropImageForm2" class="text-center" style="" >  
            <form action="upload.php" method="post" onsubmit="return cancelCrop();">
              <input type="submit" class="btn btn-danger" value="Cancel Crop" />
            </form>
	        </div>              
		    </div>
		    <!-- END CROPPING CONTAINER DIV -->
			<?php 
			} ?>
	</div>
	<!-- END UPLOAD NEW PROFILE PIC PANEL -->
	 
<?php if($result_path) { ?>
     
<img src="<?=$result_path?>" style="position:relative; width:150px; height:150px;" />
	 
<?php } ?>
 
<br /><br />

</div>
<!-- END WRAPPER DIV -->
</body>
</html>