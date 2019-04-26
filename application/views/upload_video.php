<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script>
    const siteUrl = '<?php echo site_url();?>';
    </script>
    <script src="<?php echo base_url('assets/js/upload.js');?>"></script>
</head>

<body>
    <div class="container">
        <h2>Upload new video</h2>
        <div class="col-md-8">
            <form id="upload_form" enctype="multipart/form-data" method="post">
                <input type="file" class="form-control" name="video_file" id="video_file" required=""><br>
                <input type="button" class="btn btn-sm btn-success" value="Upload File" onclick="uploadFile()">
                <progress id="progressBar" value="0" max="100" style="width:300px;"></progress>
                <h3 id="status"></h3>
                <p id="loaded_n_total"></p>
            </form>
        </div>
        <div class="col-md-8">
            <div class="row">
                <a class="btn btn-sm btn-primary pull-right" href="<?php echo site_url('/video');?>"> Show all files</a>
            </div>
        </div>
    </div>
</body>

</html>