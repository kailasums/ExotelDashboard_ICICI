@extends('layouts.common-layout')



@section('content')

<script type="text/javascript">
    window.fileinput_bdcecb6a = { "showUpload": false, "showRemove": true, "browseLabel": "Browse", "removeLabel": " ", "mainClass": "input-group-lg", "initialCaption": " ", "overwriteInitial": true, "uploadUrl": "\/backend\/web\/index.php?r=import-users%2Fupload-csv", "dropZoneEnabled": false, "showUploadedThumbs": false, "language": "en", "purifyHtml": true };
</script>

    <section id="middle">
        <div class="container">
            <div class="row">
                <div class="bg">
                    <div class="attribute-set-create">
                        <h1 class="border-title">Import Users</h1>
                        <div class="attribute-set-form">

                            <form id="upload-csv-file-form" action="/backend/web/index.php?r=import-users%2Findex"
                                method="post" enctype="multipart/form-data">
                                <input type="hidden" name="_csrf-backend"
                                    value="FqU9LNo7NPUFPIy7-qBHxFkQIqd9dGbDu1bkv9lnbJNuxG99rUkZt0dv69KrygvyL1lw7SUnCvrMY9PnrhQoqw==">
                                <div class="form-group csv-file-attachment left-form field-importusers-csvfilepath"
                                    style="display:block;">
                                    <div class='profile-upload upload-field no-preview'><span><label
                                                class="control-label" for="importusers-csvfilepath"><strong
                                                    class="mandatory">*</strong>CSV File for upload</label></span>
                                        <div class='input-file-div upload-version'><input type="hidden"
                                                name="ImportUsers[CsvFilePath]" value=""><input type="file"
                                                id="importusers-csvfilepath" class="file-loading"
                                                name="ImportUsers[CsvFilePath]" accept="csv"
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
                                        <tr data-key="2">
                                            <td width="25%">uat mapping1.csv</td>
                                            <td width="30%">08/Mar/2018</td>
                                            <td width="25%"><span class="green">Completed</span></td>
                                            <td>
                                                <div class="actions"><a id="deletebtn"
                                                        class=" grid-button userscsvDeletebtn red"
                                                        href="javascript:void(0)" title="Delete Csv Template"
                                                        data-userscsvID="2"><i class="icn-cross"></i>Delete</a>
                                                </div>
                                            </td>
                                        </tr>
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
@endsection


