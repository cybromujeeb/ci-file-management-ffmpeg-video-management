<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="./.favicon.ico">
    <title>Directory Contents </title>
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/style.css')?>">
    <script src="<?php echo base_url('/assets/js/sorttable.js')?>"></script>
</head>

<body>
    <div id="container">
        <h1> Directory Contents </h1>
        <a class=""
            style="float: right; padding: 10px; background-color: #FE4902; color: #fff; margin: 0px 10px 10px 0px"
            href="<?php echo site_url('/video/new')?>">Upload new file</a>
        <table class="sortable">
            <thead>
                <tr>
                    <th>Filename</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Date Modified</th>
                </tr>
            </thead>
            <tbody>
                <?php
         	// Checks to see if veiwing hidden files is enabled
        	if($_SERVER['QUERY_STRING'] == "hidden")
        	{
        	    $hide               = "";
        	    $ahref              = site_url('video/readfiles');
        	    $atext              = "Hide";
        	}
        	else
        	{
        	    $hide               = ".";
        	    $ahref              = site_url('video/readfiles?hidden');
        	    $atext              = "Show";
			}
			$root_folder = FCPATH.'uploads/';
            $dir         = $this->input->get('dir');
        	 // Opens directory
            if( $dir )
            {
				$root_folder = $root_folder.$dir.'/';
            }
            $myDirectory        = opendir($root_folder);
        	// Gets each entry
        	while($entryName = readdir($myDirectory))
        	{
        	    $dirArray[]         = $entryName;
        	}
            	closedir($myDirectory);
            	$indexCount         = count($dirArray);
            	sort($dirArray);
        	// Loops through the array of files
        	for($index = 0; $index < $indexCount; $index++) 
        	{
        	// Decides if hidden files should be displayed, based on query above.
        	    if(substr("$dirArray[$index]", 0, 1)!=$hide) 
                {
        	// Resets Variables
        		$favicon            = "";
        		$class              = "file";
        	// Gets File Names
        		$name               = $dirArray[$index];
        		$namehref           = $dirArray[$index];
        	   // Gets Date Modified
                if(is_file($root_folder.$dirArray[$index]))
                {
            		$modtime        = date("M j Y g:i A", filemtime($root_folder.$dirArray[$index]));
            		$timekey        = date("YmdHis", filemtime($root_folder.$dirArray[$index]));
                }
                else
                {
                    $modtime        = null;
                    $timekey        = null;
                }
        	// Separates directories, and performs operations on those directories
        		if(is_dir($root_folder.$dirArray[$index]))
        		{
    				$extn           =   "&lt;Directory&gt;";
    				$size           =   "&lt;Directory&gt;";
    				$sizekey        =   "0";
    				$class          =   "dir";
    			// Gets favicon.ico, and displays it, only if it exists.
    				if(file_exists("$namehref/favicon.ico"))
					{
						$favicon    =   " style='background-image:url($namehref/favicon.ico);'";
						$extn       =   "&lt;Website&gt;";
					}
    			// Cleans up . and .. directories
    				if($name == ".")
    				{
    				    $name       =   ". (Current Directory)"; 
    				    $extn       =   "&lt;System Dir&gt;"; 
    				    $favicon    =   " style='background-image:url($namehref/.favicon.ico);'";
    				}
    				if($name == "..")
    				{
    				    $name       =   ".. (Parent Directory)"; 
    				    $extn       =   "&lt;System Dir&gt;"; 
    				}
        		}
        		else
                {
        			// Gets file extension
        			$extn           = pathinfo($root_folder.$dirArray[$index], PATHINFO_EXTENSION);
        			// Prettifies file type
        			switch ($extn){
        			    
                        case "mp4":     $extn   =   "Video File";       break;
                        case "3gp":     $extn   =   "Video File";       break;
                        case "avi":     $extn   =   "Video File";       break;

        				case "png":     $extn   =   "PNG Image";        break;
        				case "jpg":     $extn   =   "JPEG Image";       break;
        				case "jpeg":    $extn   =   "JPEG Image";       break;
        				case "svg":     $extn   =   "SVG Image";        break;
        				case "gif":     $extn   =   "GIF Image";        break;
        				case "ico":     $extn   =   "Windows Icon";     break;
        
        				case "txt":     $extn   =   "Text File";        break;
        				case "log":     $extn   =   "Log File";         break;
        				case "htm":     $extn   =   "HTML File";        break;
        				case "html":    $extn   =   "HTML File";        break;
        				case "xhtml":   $extn   =   "HTML File";        break;
        				case "shtml":   $extn   =   "HTML File";        break;
        				case "php":     $extn   =   "PHP Script";       break;
        				case "js":      $extn   =   "Javascript File";  break;
        				case "css":     $extn   =   "Stylesheet";       break;
        
        				case "pdf":     $extn   =   "PDF Document";     break;
        				case "xls":     $extn   =   "Spreadsheet";      break;
        				case "xlsx":    $extn   =   "Spreadsheet";      break;
        				case "doc":     $extn   =   "Word Document";    break;
        				case "docx":    $extn   =   "Word Document";    break;
        
        				case "zip":     $extn   =   "ZIP Archive";      break;
        				case "htaccess":$extn   =   "Config File";      break;
        				case "exe":     $extn   =   "Executable File";  break;
        
        				default: $extn != "" ? $extn = strtoupper($extn)." File" : $extn = "Directory"; break;
        			}
        			// Gets and cleans up file size
    				$size           =   $this->ffmpeg->pretty_filesize($root_folder.$dirArray[$index]);
                    if(is_file($root_folder.$dirArray[$index]))
                    {
    				$sizekey        =   filesize($root_folder.$dirArray[$index]);
					}
					else
					{
                        $sizekey    =   null;
                    }
        		}
			// Output
			
            if(!is_dir($root_folder.$dirArray[$index]))
            {
        	    $namehref               =   'video/files?file='.$namehref;
            }
            else
            {
                $namehref               =   'video/readfiles?dir='.$dirArray[$index].'/';
            }
        	 echo("
        		<tr class='$class'>
        			<td><a href='".site_url($namehref)."' class='name'>$name</a></td>
        			<td><a href='".site_url($namehref)."'>$extn</a></td>
        			<td sorttable_customkey='$sizekey'><a href='".site_url($namehref)."'>$size</a></td>
        			<td sorttable_customkey='$timekey'><a href='".site_url($namehref)."'>$modtime</a></td>
        		</tr>");
        	   }
        	}
    	?>
            </tbody>
        </table>
        <h2> <?php echo("<a href='$ahref'>$atext hidden files</a>"); ?> </h2>
    </div>
</body>

</html>