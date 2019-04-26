<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{	
		//die('hello');
		$this->load->helper('url');
		$this->load->view('welcome_message');
	}
	
	function info()
	{
		phpinfo();
	}

	//File upload
	public function upload()
	{
		$fileName = $_FILES["file1"]["name"]; // The file name

		$fileTmpLoc = $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder

		$fileType = $_FILES["file1"]["type"]; // The type of file it is

		$fileSize = $_FILES["file1"]["size"]; // File size in bytes

		$fileErrorMsg = $_FILES["file1"]["error"]; // 0 for false... and 1 for true
		
		if (!$fileTmpLoc) 
		{ // if file not chosen
		
		    echo "ERROR: Please browse for a file before clicking the upload button.";
		    
		    exit();
		}
		
		if(move_uploaded_file($fileTmpLoc, "uploads/$fileName"))
		{
		    
		    //echo "$fileName upload is complete";
		    echo $fileName;
		    
		} 
		else 
		{    
		    echo "move_uploaded_file function failed";
		}

	}
	
	//show all files uploaded
	
   public function read_all_files()
   {
       
      $this->load->helper('url');
      $this->load->model('ffmpeg_model');
      $this->load->view('showfiles');
      
    }
    
    
    //show uploaded file
    
    public function files()
    {
        $this->load->helper('url');
        
        $data['file'] = $this->input->get('file');
        
        $this->load->view('manage_files', $data);
    }
    
    //function to cut videos
    
    public function video_cut()
    {

    	$this->load->helper('url');
        //print_r($_POST);
        
        
        //$input_dir = dirname(__FILE__). "/input/";
        
	    $input_dir = "uploads";
	    
	    $output_dir = "uploads/cutvideos";
	
	if(isset($_POST["submit"])) 
	{

		if(file_exists($input_dir.'/'.$this->input->post('videofile')))
		{
			
			$fileType = mime_content_type($input_dir.'/'.$this->input->post('videofile'));

			if(!preg_match('/video\/*/', $fileType)) {	

				echo "Please upload a video";

				return;
			}

			
			// file name with extension
			$file = $_POST["videofile"];	
			
			// name without extension
			$filename = pathinfo($file, PATHINFO_FILENAME);
			
			// Default extension
			$default = pathinfo($file, PATHINFO_EXTENSION);
			
			// create special string from date to ensure filename is unique
			$date = date("Y-m-d H:i:s");

			$uploadtime = strtotime($date);
			
			// upload path
			$video_file = $input_dir.'/'.$this->input->post('videofile');
			
			// check the specified extension
			if(!isset($_POST["extension"]) || $_POST["extension"] == "")
			{
				echo "Please set the output extension.";
				return;
			}

			$ext = $_POST["extension"]; // output extension	

			if($ext == "none") 
			{
				$ext = $default;
			}			
			
			// put file to input directory to make it easier to be processed with ffmpeg
			$moved = true;//move_uploaded_file($temp_file, $video_file);

			if($moved) 
			{
				// change php working directory to where ffmpeg binary file reside
				//chdir("binaries");
				
				$start_from = "00:00:00";				
				// check the specified starting time
				if(isset($_POST["start_from"]) && $_POST["start_from"] != "")
				{
					$start_from = $_POST["start_from"];
				}				
				
				$length = "10";
				// check the specified duration
				if(isset($_POST["length"]) && $_POST["length"] != "")
				{
					$length = $_POST["length"];
				}
				
				$output = "$output_dir/$uploadtime"."_$filename.$ext";

				//echo 'file name : '.$filename;

				//echo 'output file name : '.$output;

				//ffmpeg -i movie.mp4 -ss 00:00:03 -t 00:00:08 -async 1 cut.mp4

				$process = exec("ffmpeg -t $length -ss $start_from -i $video_file -b:v 2048k $output 2>&1", $result);	
				//print_r($process);			
				
				// delete uploaded file from input folder to reserve disk space
				//unlink($video_file);
				
				echo "<span>Edit Finished:</span>";
				
				//echo "<a href='http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"."cutvideos/$uploadtime"."_$filename.$ext'>Download</a>";

				echo "<a href='http://$_SERVER[HTTP_HOST]"."/uploads/cutvideos/$uploadtime"."_$filename.$ext'>Download</a>";
			}
			
		} else {

			echo "<h3>No file was uploaded!</h3>";
		}
	}

    }

    
}
