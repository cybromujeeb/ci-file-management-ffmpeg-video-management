<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2019, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Ffmpeg Class
 *
 * This class enables the creation of Ffmpeg
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://ffmpeg.org/ffmpeg.html
 */

class CI_Ffmpeg {

	/**
	 * Ffmpeg class
	 *
	 * @var mixed
	 */
	private $video_file 				= '';
	private $start_time 				= '';
	private $end_time 					= '';
	private $input_directory 			= '';
	private $output_directory			= '';
	private $output_filename 			= '';
	private $extension 					= '';
	private $__response 				= array(
											"error" 	=> false,
											"message" 	=> "File converted successfully"
										);

	// --------------------------------------------------------------------

	/**
	 * CI Singleton
	 *
	 * @var object
	 */
	protected $CI;

	// --------------------------------------------------------------------

	/**
	 * Class constructor
	 *
	 *
	 * 
	 * @param	array	$config	Ffmpeg options
	 * @return	void
	 */

	public function __construct($config = array())
	{
		$this->CI =& get_instance();
		empty($config) OR $this->initialize($config);
		log_message('info', 'Ffmpeg Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the user preferences
	 *
	 * Accepts an associative array as input, containing display preferences
	 *
	 * @param	array	config preferences
	 * @return	CI_Ffmpeg
	 */
	public function initialize($config = array())
	{
		foreach ($config as $key => $val)
		{
			if (isset($this->$key))
			{
				$this->$key = $val;
			}
		}
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Generate the Ffmpeg
	 * @return	array
	 */

	public function convert( $params = array() )
	{
		// checks the file is exists  
		if(file_exists($this->video_file))
		{
			if(!preg_match('/video\/*/', mime_content_type($this->video_file)))
			{
				$this->__response['error'] 		= true;
				$this->__response['message'] 	= "Please upload a video file";
				return $this->__response;
			}
			
			// check the specified extension
			if(!$this->extension)
			{
				$this->extension 				= pathinfo($this->video_file, PATHINFO_EXTENSION);
			}

			if(!$this->output_filename)
			{
				$this->output_filename 			= pathinfo($this->video_file, PATHINFO_FILENAME);
			}

			if(empty($this->start_time))
			{
				$this->start_time 				= '00:00:00';
			}

			if(empty($this->end_time))
			{
				$this->__response['error'] 		= true;
				$this->__response['message'] 	= "Please specify end time";
				return $this->__response;
			}

			if(is_array($this->start_time) && is_array($this->end_time))
			{
				$output 						= array();
				for($i = 0; $i < count($this->end_time); $i++)
				{
					$output[] 					= $this->output_directory.'/'.strtotime('now').$i.'_'.$this->output_filename.'.'.$this->extension;
					$end_time 					= $this->end_time[$i];
					$start_time 				= $this->start_time[$i];
												  exec("ffmpeg -t $end_time -ss $start_time -i $this->video_file -strict -2 -b:v 2048k $output[$i] 2>&1");
				}
			}
			else
			{
				$output 						= $this->output_directory.'/'.strtotime('now').'_'.$this->output_filename.'.'.$this->extension;
				$process 						= exec("ffmpeg -t $this->end_time -ss $this->start_time -i $this->video_file -strict -2 -b:v 2048k $output 2>&1", $result);
			}
			$this->__response['cut_video'] 		= $output;
			return $this->__response;
		}
		else
		{
			$this->__response['error'] 			= true;
			$this->__response['message'] 		= "No video file was initialized!";
			return $this->__response;
		}
	}


	//Adds pretty filesizes
    public function pretty_filesize($file) 
    	{
            if(is_file($file))
            {
        		$size 							= filesize($file);
				if( $size < 1024 ) 
				{
					$size 						= $size." Bytes";
        		}
        		elseif(( $size < 1048576 ) && ( $size > 1023 ))
        		{
        		    $size 						= round( $size / 1024, 1)." KB";
        		}
        		elseif(( $size < 1073741824 ) && ( $size > 1048575 ))
        		{
        		    $size 						= round( $size / 1048576, 1)." MB";
        		}
        		else
        		{
        		    $size 						= round( $size / 1073741824, 1)." GB";
				}
        		return $size;
        	}
            return null;
        }

	

}
