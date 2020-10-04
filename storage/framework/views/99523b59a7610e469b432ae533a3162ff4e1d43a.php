<?php $__env->startSection('content'); ?>

<script type="text/javascript">
    window.fileinput_bdcecb6a = { "showUpload": false, "showRemove": true, "browseLabel": "Browse", "removeLabel": " ", "mainClass": "input-group-lg", "initialCaption": " ", "overwriteInitial": true, "uploadUrl": "\/backend\/web\/index.php?r=import-users%2Fupload-csv", "dropZoneEnabled": false, "showUploadedThumbs": false, "language": "en", "purifyHtml": true };
</script>

    <section id="middle">
        <div class="container">
            <div class="row">
                <div class="bg">
                    <div class="attribute-set-create">
                        <h1 class="border-title">Import Users</h1>
                        <div id="success-data"> 
                        <?php if(\Session::has('success')): ?>
                                <div class="alert alert-success">
                                <?php echo e(\Session::get('success')); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <div id="error-data"> 
                        <?php if(\Session::has('error')): ?>
                                <div class="alert alert-danger">
                                <?php echo e(\Session::get('error')); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="attribute-set-form">

                            <form id="upload-csv-file-form" action="/admin/upload-file"
                                method="post" enctype="multipart/form-data">
                                <?php echo e(@csrf_field()); ?>

                                <div class="form-group csv-file-attachment left-form field-importusers-csvfilepath"
                                    style="display:block;">
                                    <div class='profile-upload upload-field no-preview'><span><label
                                                class="control-label" for="importusers-csvfilepath"><strong
                                                    class="mandatory">*</strong>CSV File for upload</label></span>
                                        <div class='input-file-div upload-version'><input type="hidden"
                                                name="ImportUsers[CsvFilePath]" value=""><input type="file"
                                                id="importusers-csvfilepath" class="file-loading"
                                                name="file" accept="csv"
                                                data-krajee-fileinput="fileinput_bdcecb6a">
                                            <!--[if lt IE 10]><br><div class="alert alert-warning"><strong>Note:</strong> Your browser does not support file preview and multiple file upload. Try an alternative or more recent browser to access these features.</div><script>document.getElementById("importusers-csvfilepath").className.replace(/\bfile-loading\b/,"");;</script><![endif]-->
                                            <div class="help-block"></div>
                                        </div>
                                        <div class='uploadBtn'><button type="submit" id="submit-import-users-btn"
                                                class="btn">Upload</button></div>
                                    </div>
                                </div>

                                <input type="hidden" id="importusers-currentcsvfile"
                                    name="ImportUsers[currentCsvFile]" readOnly><input type="hidden"
                                    id="importusers-tempcsvfile" name="ImportUsers[tempCsvFile]" value="" readOnly>

                            </form>
                        </div>

                    </div>
                    <div class="import-users-index">
                        <h1>Import History</h1>
                        <div id="import-users-listing-div" data-pjax-container="" data-pjax-push-state
                            data-pjax-timeout="1000">
                            <div id="w0" class="grid-view">
                                <div class="summary">Showing <b>1-2</b> of <b>2</b> items.</div>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th><a href="/backend/web/index.php?r=import-users%2Findex&amp;sort=CsvFilePath"
                                                    data-sort="CsvFilePath">CSV File</a></th>
                                            <th><a href="/backend/web/index.php?r=import-users%2Findex&amp;sort=RequestedOn"
                                                    data-sort="RequestedOn">Requested On</a></th>
                                            <th><a href="/backend/web/index.php?r=import-users%2Findex&amp;sort=Status"
                                                    data-sort="Status">Status</a></th>
                                            <th class="action-column">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php 
                                    if(count($fileUploadRecord) > 0 ){
                                        for($i = 0 ; $i < count($fileUploadRecord); $i++){
                                            ?>
                                            <tr data-key="2">
                                                <td width="25%"><?php
                                                
                                                if($fileUploadRecord[$i]['file_name']){
                                                    if($i === 0 ){
                                                        echo "<a target='blank' href='".url("/storage/".env("IMPORTFILESTORAGENAME"))."'>".$fileUploadRecord[$i]['file_name']."</a>";
                                                    }else{
                                                        echo $fileUploadRecord[$i]['file_name'];
                                                    }
                                                } 
                                                ?></td>
                                                <td width="30%"><?php echo ($fileUploadRecord[$i]['created_at'] ? date("Y-m-d H:i:s", strtotime($fileUploadRecord[$i]['created_at'])) : '');?></td>
                                                <td width="25%"><?php echo ($fileUploadRecord[$i]['upload_status'] ? $fileUploadRecord[$i]['upload_status'] : '');?></td>
                                                <td>
                                                    <div class="actions">
                                                        <?php
                                                            if($i === 0){
                                                                if($fileUploadRecord[$i]['upload_status'] === 'failed') {
                                                                    echo $fileUploadRecord[$i]['remark'];
                                                                }
                                                                 
                                                                if($fileUploadRecord[$i]['upload_status'] === 'completed'){
                                                                    echo "<a target='blank' href='/admin/export-log'>Log File</a>|";  
                                                                    if(env("DOWNLOADPASSWORDLINK")  === "YES"){
                                                                        echo "<a target='blank' href='/admin/export-password'>PasswordFile</a>";    
                                                                    }
                                                                }
                                                            }
                                                        ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php     
                                        }
                                    }
                                    
                                    ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.common-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ISPCalling/resources/views/admin/registeruser.blade.php ENDPATH**/ ?>