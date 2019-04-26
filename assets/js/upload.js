
/* Javacsript progress bar file uploads */

function _(el){
    return document.getElementById(el);
}

function uploadFile(){
    var file 						= _("video_file").files[0];
    if(!file){
        return false;
    }
    var formdata 					= new FormData();
    formdata.append("video_file", file);
    var ajax 						= new XMLHttpRequest();
    ajax.upload.addEventListener("progress", progressHandler, false);
    ajax.addEventListener("load", completeHandler, false);
    ajax.addEventListener("error", errorHandler, false);
    ajax.addEventListener("abort", abortHandler, false);
    ajax.open("POST", siteUrl+'/video/upload');
    ajax.send(formdata);
} 

function progressHandler(event){
    _("loaded_n_total").innerHTML 	= "Uploaded "+event.loaded+" bytes of "+event.total;
    var percent = (event.loaded / event.total) * 100;
    _("progressBar").value 			= Math.round(percent);
    _("status").innerHTML 			= Math.round(percent)+"% uploaded... please wait";
}

function completeHandler(event){
        let reponse 				= JSON.parse(event.target.responseText);
        _("status").innerHTML 		= reponse.message;
        _("progressBar").value 		= 0;
        console.log(reponse);
        if(!reponse.error){
            setTimeout(()=>{
                location.replace(siteUrl+'/video?file='+reponse.uniq_name);
            }, 1000);
        }
}

function errorHandler(event){
    _("status").innerHTML 			= "Upload Failed";
}

function abortHandler(event){
    _("status").innerHTML 			= "Upload Aborted";
}