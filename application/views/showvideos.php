<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <title>All uploaded videos</title>
    </head>
<body>
    <?php if(!empty($videos)):?>
    <div class="container">
        <div class="col-md-12 col-lg-12 col-sm-12 align-items-center">
            <h3>Videos uploaded</h3>
        </div>
    </div>
    <div class="container">
        <div class="row">
        <?php foreach($videos as $video):
            if(!empty($video->video_path) && file_exists($video->video_path)):?>
            <div class="col-md-2 col-lg-2 col-sm-4 col-xs-4">
            <?php 
                $filetype = mime_content_type($video->video_path);
                if(in_array('video',explode("/",$filetype))): ?>
                <a href="<?php echo site_url('video?file='.$video->video_random_key);?>">
                    <video width="220" height="140">
                        <source src="<?php echo base_url($video->video_path);?>" type="<?php echo $filetype;?>">
                    </video>
                </a>
                <?php endif;?>
            </div>
            <?php endif; endforeach;?>  
        </div>
        <p></p>
        <div class="row">
            <a href="<?php echo site_url('video/new');?>" class="btn btn-sm btn-success">Upload new video</a>
            <a href="<?php echo site_url('video?converted=1');?>" class="btn btn-sm btn-primary pull-right">View all converted videos</a>
        </div>
        <?php else:?>
        <div class="container">
            <div class="row">
                <h3>No videos found</h3>
            </div>
            <div class="row">
                <a href="<?php echo site_url('video/new');?>" class="btn btn-sm btn-primary">Upload new video</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
    </body>
</html>