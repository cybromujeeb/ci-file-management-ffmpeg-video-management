<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends CI_Controller {

	public function index()
	{	
		$this->load->helper('url');
		$this->load->model('ffmpeg_model');
		$this->load->library('ffmpeg');
		$this->load->library('session');

		$uniq_file_name						= $this->input->get('file');
		$converted 							= $this->input->get('converted');

		if(!empty($uniq_file_name) && !is_array($uniq_file_name))
		{
			$data['video'] 					= $this->ffmpeg_model->get_video($uniq_file_name);
			$this->load->view('manage_video', $data);
			return;
		}
		if(is_array($uniq_file_name))
		{
			$data['videos'] 				= $this->ffmpeg_model->get_videos(true, $uniq_file_name);
		}
		else if(!empty($converted))
		{
			$data['videos'] 				= $this->ffmpeg_model->get_videos(true);
		}
		else
		{
			$data['videos'] 				= $this->ffmpeg_model->get_videos();
		}
      	$this->load->view('showvideos', $data);
	}

	public function readfiles()
	{
		$this->load->helper('url');
		$this->load->library('ffmpeg');
		$this->load->view('showfiles');
	}
	
	function info()
	{
		phpinfo();
	}

	public function convert()
	{
		$this->load->library('ffmpeg');
		$this->load->model('ffmpeg_model');
		$this->load->library('session');

		$output_directory 					= 'uploads/cutvideos/'.date('mY');
		if ( !file_exists( $output_directory ) && !is_dir( $output_directory ) ) 
		{
			mkdir( $output_directory, 0755);
		}
		$video_uniq_name 					= $this->input->post('video_uniq_name');
		$video_file 						= $this->input->post('video_file');
		$this->ffmpeg->initialize(
	 		array(
	 			'video_file' 				=> $video_file,
	 			'start_time' 				=> $this->input->post('start_time'),
	 			'end_time' 					=> $this->input->post('end_time'),
	 			'extension'					=> $this->input->post('extension'),
	 			'output_filename'			=> pathinfo($video_file, PATHINFO_FILENAME).date('d-m-Y-H-i-s'),
	 			'output_directory' 			=> $output_directory
	 		)
		);

		$result 							= $this->ffmpeg->convert();
		if(!$result['error'])
		{
			if(is_array($result['cut_video']))
			{
				$video = array();
				for($i=0; $i < count($result['cut_video']); $i++)
				{
					$video['uniq_name'][] 	= $this->ffmpeg_model->add_video_file($result['cut_video'][$i], true);
				}
			}
			else
			{
					$video['uniq_name']   	= $this->ffmpeg_model->add_video_file($result['cut_video'], true);
			}

			//function for ajax converts
			$this->ajaxconvert($video['uniq_name']);

			return true;

			if(is_array($video['uniq_name']))
			{
				$query = '';
				for($i=0; $i < count($video['uniq_name']); $i++)
				{
					$query 					.= 'file[]='.$video['uniq_name'][$i].'&';
				}
				redirect('video?'.$query);
				return true;
			}
			redirect('video?file='.$video['uniq_name']);
			return true;
		}
		$this->session->set_flashdata('notification', array(
															'type' 		=> 'danger', 
															'message' 	=> 'video convertion failed'
														));
		redirect('video?file='.$video_uniq_name);
	}

	//File upload
	public function upload()
	{
		$this->load->model('ffmpeg_model');
		$this->load->helper('security');
		$this->load->library('session');
		$this->load->library('upload');

		$file_name 							= $_FILES["video_file"]["name"];

		$upload_path = 'uploads/'.date('mY');
		if ( !file_exists( $upload_path ) && !is_dir( $upload_path ))
		{
			mkdir( $upload_path, 0755);
		}
		$file_path 							= $upload_path.'/'.$file_name;
		$this->upload->initialize(
			array(
				'upload_path' 				=> $upload_path,
				'max_size'					=> '102400',
				'allowed_types'				=> 'mp4', //['mov','mp4','m4a','3gp','3g2','mj2'],
				'remove_spaces' 			=> true,
				'overwrite' 				=> true,
				'detect_mime'				=> true,
				'file_name' 				=> $file_name
			)
		);

		header("Content-type: application/json;");

			if (!$this->upload->do_upload('video_file'))
			{
				echo json_encode(array(
									'error' 	=> true, 
									'message' 	=> $this->upload->display_errors()
									));
			    return false;
			}
			if($uniq_key = $this->ffmpeg_model->add_video_file($file_path))
			{
				echo json_encode(array(
									'error' 	=> false, 
									'message' 	=> 'successfully uploaded', 
									'file_name' => $file_name, 
									'uniq_name' => $uniq_key
									));
				return true;
			}
			echo json_encode(array(
								'error' 		=> true, 
								'message' 		=> 'upload failed'
							));
			return false;
	}
	
	//show all files uploaded
	
   public function new()
   {
  		$this->load->helper('url');
		$this->load->view('upload_video');
   }

   public function ajaxconvert($conveted_videos=array())
   {
	$this->load->model('ffmpeg_model');
	$videos				= $this->ffmpeg_model->get_videos(true, $conveted_videos);
	  ?> 
		<div class="row">
        <?php foreach($videos as $video):
            if(!empty($video->video_path) && file_exists($video->video_path)):?>
            <div class="col-md-2 col-lg-2 col-sm-4 col-xs-4">
            <?php 
                $filetype = mime_content_type($video->video_path);
                if(in_array('video',explode("/",$filetype))): ?>
                <a href="<?php echo site_url('video?file='.$video->video_random_key);?>">
                    <video width="220" height="140" controls>
                        <source src="<?php echo base_url($video->video_path);?>" type="<?php echo $filetype;?>">
                    </video>
                </a>
                <?php endif;?>
            </div>
            <?php endif; endforeach;?>  
        </div>
	<?php
   }
}
