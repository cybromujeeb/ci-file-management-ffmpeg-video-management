<!doctype html>
<html>
  <head> 
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="./.favicon.ico">
    <title>Manage files</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.4.0.min.js" integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg=" crossorigin="anonymous"></script>
    <style>
      .rows{
        padding :5px;
      }

      .loader {
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
        
      }

      .xm{
        position: absolute;
        margin: 17% 0 0 48%;
      }

      .xm1{
        position: absolute;
        margin: 27% 0 0 48%;
      }

      .loading{
        display:none;
      }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    </style>
    <script>
      const siteUrl = "<?php echo site_url()?>";
      function validate(el){
        document.getElementById("convert_form").addEventListener("submit", function(event){
          event.preventDefault()
        });
        $('.loading').show();
        $('#submit').attr("disabled", true);
          $.ajax({
              type    : 'POST',
              url     : siteUrl + '/video/convert',
              data    : $('#convert_form').serialize(),
              success : function(data){
                $('#form-datas').html(data);
                $('.loading').hide();
              }
          });
        return false;
      }
    </script>
  </head>
  <body>
    <div class="loading">
      <div class="loader xm"></div>
          <div style="text-align:center;" class="xm1">
          <strong>Please wait......</strong>
      </div>
    </div>
    <section class="container">
    <h1>Manage Video <?php echo pathinfo($video->video_path, PATHINFO_FILENAME).'.'.pathinfo($video->video_path, PATHINFO_EXTENSION);?></h1>
      <div class="container">
        <?php $notifications = $this->session->flashdata('notification'); 
          if(!empty($notifications)): ?>
          <div class="alert alert-<?php echo $notifications['type'];?> alert-dismissible" role="alert">
            <strong><?php echo $notifications['message'];?></strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$('.alert').remove();">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
        <?php endif; ?>
        <div class="row" style="text-align:center;">
          <h3>Video cutter</h3>
        </div>
        <div class="row">
          <form onsubmit="return validate($(this))" id="convert_form" action="<?php echo site_url('video/convert');?>" method="POST">
            <div class="col-sm-4">
              <?php
              if(empty($video) || !file_exists($video->video_path)): redirect('video'); return; endif;
              $filetype = mime_content_type($video->video_path);
              if(in_array('video',explode("/",$filetype))):?>
                <video width="320" height="340" controls>
                    <source src="<?php echo base_url($video->video_path);?>" type="<?php echo $filetype;?>">
                </video>
              <?php else: ?>
                  <img src="<?php echo base_url($video->video_path);?>" width="75px">
              <?php endif; ?>
            </div>
            <div id="form-datas" style="">
            <input type="hidden" value="<?php echo $video->video_path;?>" name="video_file"/>
            <input type="hidden" value="<?php echo $video->video_random_key;?>" name="video_uniq_name"/>
            <br/>
            <div class="col-md-8" id="appendels">
              <div class="col-sm-4">
                <div class = "form-group">
                  <label class="form-inline"> From: <input class="form-control" type="time" name="start_time[]" step="1" value="00:00:00" required=""/></label>
                </div>
              </div>
              <div class="col-sm-3">
                <div class = "form-group">
                  <label class="form-inline">To: <input class="form-control" type="time" name="end_time[]" step="1" value="00:00:10" required=""/></label>
                </div>
              </div>
              <div class="col-sm-2">
                  <a href="#" class="btn btn-sm btn-primary" onclick="addtabs()"><span class="glyphicon glyphicon-plus"></span></a>
              </div>
              <div class="row"> </div>
            </div>
            <div id="appendelss" class="overflow-auto"></div>
            <div class="col-sm-12">
              <div class="col-md-8 pull-right">
                <div class="col-md-6">
                  <label class="form-inline">Output extension: 
                    <select class="form-control" name="extension">
                      <option value="">Default</option>
                      <option value="mp4">Mp4</option>
                      <option value="m4a">M4a</option>
                    </select>
                  </label>
                </div>
                <div class="col-md-2 pull-right">
                  <button id="submit" tyupe="submit" name="submit" class="btn btn-sm btn-success">Cut the video</button>
                </div>
              </div>
            </div>
            </div>
            <div class="col-sm-12">
              <hr/>
                <div class="col-md-8 pull-right">
                    <a href="<?php echo site_url('/video');?>" class="btn btn-sm btn-primary">Show all videos</a>
                    <a href="<?php echo site_url('/video/new');?>" class="btn btn-sm btn-warning pull-right">Upload new video</a>
                </div>
              </div>
            </div>
          </form>
      </div>
    </section>
   <script>    
      function addtabs(){
        $('#appendels').append(`
        <div class="row rows">
          <div class="col-sm-4">
          <label class="form-inline">From: <input class="form-control" name="start_time[]" type="time" step="1" value="" required=""/></label>
          </div>
          <div class="col-sm-3">
          <label class="form-inline">To: <input class="form-control" name="end_time[]" type="time" step="1" value="" required=""/></label>
          </div>
          <div class="col-sm-2">
          <a class="btn btn-xs btn-warning pullright" onclick="removeTab($(this))"><span class="glyphicon glyphicon-trash" ></span></a>
          </div>
          <br/>
        </div>`);
      }
      function removeTab(el){
        el.closest(".rows").remove();
      }           
    </script>
  </body>
</html>