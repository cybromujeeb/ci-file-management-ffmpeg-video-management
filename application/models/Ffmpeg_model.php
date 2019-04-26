<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ffmpeg_model extends CI_Model {
    
    // insert video file path 
    public function add_video_file($video_path, $converted = false) 
	{
		$uniq_key = uniqid();
		$this->db->insert('videos', $this->security->xss_clean(array('video_path' => $video_path, 'video_random_key' => $uniq_key, 'converted' => $converted)));
		
		if($this->db->insert_id())
		{
			return $uniq_key;
		}
	}

	//get video by uniq file name

	public function get_video($uniq_file_name)
	{
		return $this->db->where('video_random_key', $uniq_file_name)->get('videos')->row();
	}

	//get all videos uploaded
	public function get_videos($converted = false, $uniq_file_names = array())
	{	
		if(!empty($uniq_file_names) && is_array($uniq_file_names))
		{
			$this->db->where_in('video_random_key', $uniq_file_names);
		}
		return $this->db->where('converted', $converted)->get('videos')->result();
	}
}