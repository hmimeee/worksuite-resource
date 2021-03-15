@extends('layouts.member-app')

@section('page-title')
<div class="row bg-title">
	<div class="col-lg-8 col-md-5 col-sm-6 col-xs-12">
		<h4 class="page-title"><i class="{{ $pageIcon ?? '' }}"></i> {{ $pageTitle }}</h4>
	</div>

	<div class="col-lg-4 col-sm-6 col-md-7 col-xs-12 text-right">
		<ol class="breadcrumb" style="display: inline-block !important;">
			<li style="font-size: 16px;"><a style="color:rgb(255, 47, 0);" href="{{ route('member.resources.trash') }}"><i class="fa fa-trash"></i> Trash ({{ $trashCount }})</a></li>
		</ol>
	</div>
</div>
@endsection

@push('head-script')
<style>
.stage{
  max-width:90%;margin: 5%;
  position:relative;  
}
.folder-wrap{
  display: flex;
  flex-wrap:wrap;
}
.tile{
    border-radius: 3px;
    width: calc(20% - 17px);
    margin-bottom: 23px;
    text-align: center;
    border: 1px solid #eeeeee;
    transition: 0.2s all cubic-bezier(0.4, 0.0, 0.2, 1);
    position: relative;
    padding: 10px;
    margin-right: 17px;
}
.tile:hover{
  box-shadow: 0px 10px 10px -6px rgba(0, 0, 0, 0.12);
}
.tile i{
    color: #00A8FF;
    height: 55px;
    margin-bottom: 20px;
    font-size: 55px;
    display: block;
    line-height: 54px;
    cursor: pointer;
}

.it .btn-orange
{
	background-color: transparent;
	border-color: #777!important;
	color: #777;
	text-align: left;
    width:100%;
}
.it input.form-control
{
	height: 54px;
	border:none;
    margin-bottom:0px;
	border-radius: 0px;
	border-bottom: 1px solid #ddd;
	box-shadow: none;
}
.it .form-control:focus
{
	border-color: #00A8FF;
	box-shadow: none;
	outline: none;
}
.fileUpload {
    position: relative;
    overflow: hidden;
    margin: 10px;
}
.fileUpload input.upload {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
}
.it .btn-new, .it .btn-next
{
	margin: 30px 0px;
	border-radius: 0px;
	background-color: #333;
	color: #f5f5f5;
	font-size: 16px;
	width: 155px;
}
.it .btn-next
{
	background-color: #00A8FF;
	color: #fff;
}
.it .btn-check
{
  cursor:pointer;
  line-height:54px;
  color:rgb(255, 47, 0);
}
.it .uploadDoc
{
	margin-bottom: 20px;
}
.it .uploadDoc
{
	margin-bottom: 20px;
}
.it .btn-orange img {
    width: 30px;
}
p
{
  font-size:16px;
  text-align:center;
  margin:30px 0px;
}
.it #uploader .docErr
{
	position: absolute;
    right:auto;
    left: 10px;
    top: -56px;
    padding: 10px;
    font-size: 15px;
    background-color: #fff;
    color: red;
    box-shadow: 0px 0px 7px 2px rgba(0,0,0,0.2);
    display: none;
}
.it #uploader .docErr:after
{
	content: '\f0d7';
	display: inline-block;
	font-family: FontAwesome;
	font-size: 50px;
	color: #fff;
	position: absolute;
	left: 30px;
	bottom: -40px;
	text-shadow: 0px 3px 6px rgba(0,0,0,0.2);
}
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="white-box">
            @if(auth()->user()->hasRole('admin'))
            <div class="container">
                <div class="row it">
                    <div class="col-sm-offset-1 col-sm-10" id="one">
                        <form id="uploadDoc-form" action="{{ route('member.resources.store') }}">
                            @csrf
                            <div id="uploader">
                                <p class="text-muted">Allowed files: pdf, docx, svg, jpg, jpeg, png, gif, mp4, txt, xlsx, xls, doc, ppt, zip, psd, ai, eps</p>
                                <div class="row uploadDoc">
                                    <div class="col-sm-3">
                                        <div class="docErr">Please upload valid file</div>
                                        <!--error-->
                                        @php($rand = rand(1,100))
                                        <div class="fileUpload btn btn-orange">
                                            <img src="https://image.flaticon.com/icons/svg/136/136549.svg" class="icon">
                                            <span class="upl" id="upload">Upload document</span>
                                            <input type="file" name="file_{{ $rand }}" class="upload up" id="up" onchange="readURL(this);" />
                                        </div><!-- btn-orange -->
                                    </div><!-- col-3 -->
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="details_{{ $rand }}"
                                            placeholder="Details (optional) - max 255 charecters">
                                    </div>
                                    <!--col-8-->
                                    <div class="col-sm-1"><a class="btn-check"><i class="fa fa-times"></i></a></div>
                                    <!-- col-1 -->
                                </div>
                                <!--row-->
                            </div>
                            <!--uploader-->
                            <div class="text-center">
                                <a class="btn btn-new"><i class="fa fa-plus"></i> Add more</a>
                                <button class="btn btn-next"><i class="fa fa-paper-plane"></i> Submit</button>
                            </div>
                        </form>
                    </div>
                    <!--one-->
                </div><!-- row -->
            </div><!-- container -->
            @endif
        </div>
    </div>

    <div class="col-md-12">
        <div class="white-box">
            <div class="stage">
                <div class="folder-wrap">
                    @forelse($resources as $resource)
                    <div class="tile form">
                        <i class="fa {{ $icons[$resource->ext] ?? 'fa-file' }}"></i>
                        {{-- <h3>{{ $resource->name }}</h3> --}}
                        <p>{{ $resource->details ?? '...' }}</p>
                        <p><a href="{{ route('member.resources.show', $resource->id) }}">Download</a>
                            @if(auth()->user()->hasRole('admin'))| <a id="deleteFile" href="{{ route('member.resources.delete', $resource->id) }}">Delete</a> @endif
                        </p>
                    </div>
                    @empty
                    <div style="font-size: 20px;">
                        <img src="{{ asset('empty-box.gif') }}" height="150px" alt=""/>
                        The resource box is empty!
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="col-md-6">
            </div>
            <div class="col-md-6" align="right">
                {{ $resources->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('footer-script')
<script>
    $('#uploadDoc-form').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        console.log(formData);
        $.ajax({
            method: 'POST',
            container: '#uploadDoc-form',
            contentType: false,
            processData: false,
            data: formData,
            success: function (response) {
                if (response.status == "success") {
                    swal("Success!", response.message, "success");
                    setTimeout(function () { location.reload(true); }, 1000);
				} else {
					swal("Warning!", response.message, "warning");
				}
            }
        });
    });

    $('body #deleteFile').click(function(e) {
        e.preventDefault();
        $.easyAjax({
            url: $(this).attr('href'),
            type: "POST",
            data: {'_token': "{{ csrf_token() }}", '_method': "DELETE"},
            success: function(response) {
                if (response.status == "success") {
                    swal("Success!", response.message, "success");
                    setTimeout(function () { location.reload(true); });
				} else {
					swal("Warning!", response.message, "warning");
				}
            }
        })
    });

    var fileTypes = ['pdf', 'docx', 'svg', 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'txt', 'xlsx', 'xls', 'doc', 'ppt', 'zip', 'psd', 'ai', 'eps']; //acceptable file types
    function readURL(input) {
        if (input.files && input.files[0]) {
            var extension = input.files[0].name.split('.').pop().toLowerCase(), //file extension from input file
                isSuccess = fileTypes.indexOf(extension) > -1; //is extension in acceptable types

            if (isSuccess) { //yes
                var reader = new FileReader();
                reader.onload = function (e) {
                    if (extension == 'pdf') {
                        $(input).closest('.fileUpload').find(".icon").attr('src', 'https://image.flaticon.com/icons/svg/179/179483.svg');
                    } else if (extension == 'docx') {
                        $(input).closest('.fileUpload').find(".icon").attr('src', 'https://image.flaticon.com/icons/svg/281/281760.svg');
                    } else if (extension == 'mp4') {
                        $(input).closest('.fileUpload').find(".icon").attr('src', 'https://www.flaticon.com/svg/static/icons/svg/1719/1719843.svg');
                    } else if (extension == 'png') {
                        $(input).closest('.fileUpload').find(".icon").attr('src', 'https://image.flaticon.com/icons/svg/136/136523.svg');
                    }  else if (extension == 'gif') {
                        $(input).closest('.fileUpload').find(".icon").attr('src', 'https://www.flaticon.com/svg/static/icons/svg/29/29579.svg');
                    } else if (extension == 'jpg' || extension == 'jpeg') {
                        $(input).closest('.fileUpload').find(".icon").attr('src', 'https://image.flaticon.com/icons/svg/136/136524.svg');
                    } else if (extension == 'txt') {
                        $(input).closest('.fileUpload').find(".icon").attr('src', 'https://image.flaticon.com/icons/svg/136/136538.svg');
                    } else if (extension == 'xlsx') {
                        $(input).closest('.fileUpload').find(".icon").attr('src', 'https://www.flaticon.com/svg/static/icons/svg/303/303850.svg');
                    } else if (extension == 'xls') {
                        $(input).closest('.fileUpload').find(".icon").attr('src', 'https://www.flaticon.com/svg/static/icons/svg/337/337958.svg');
                    } else if (extension == 'doc') {
                        $(input).closest('.fileUpload').find(".icon").attr('src', 'https://www.flaticon.com/svg/static/icons/svg/337/337958.svg');
                    } else if (extension == 'svg') {
                        $(input).closest('.fileUpload').find(".icon").attr('src', 'https://www.flaticon.com/svg/static/icons/svg/337/337954.svg');
                    } else if (extension == 'zip') {
                        $(input).closest('.fileUpload').find(".icon").attr('src', 'https://www.flaticon.com/svg/static/icons/svg/337/337960.svg');
                    } else if (extension == 'ppt') {
                        $(input).closest('.fileUpload').find(".icon").attr('src', 'https://www.flaticon.com/svg/static/icons/svg/337/337949.svg');
                    } else if (extension == 'psd') {
                        $(input).closest('.fileUpload').find(".icon").attr('src', 'https://www.flaticon.com/svg/static/icons/svg/167/167525.svg');
                    } else if (extension == 'ai') {
                        $(input).closest('.fileUpload').find(".icon").attr('src', 'https://www.flaticon.com/svg/static/icons/svg/688/688064.svg');
                    } else if (extension == 'eps') {
                        $(input).closest('.fileUpload').find(".icon").attr('src', 'https://www.flaticon.com/svg/static/icons/svg/337/337933.svg');
                    } else {
                        $(input).closest('.uploadDoc').find(".docErr").slideUp('slow');
                    }
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                //console.log('here=>'+$(input).closest('.uploadDoc').find(".docErr").length);
                $(input).closest('.uploadDoc').find(".docErr").fadeIn();
                $(input).closest('.fileUpload').find(".icon").attr('src', 'https://www.flaticon.com/svg/static/icons/svg/752/752755.svg');

                setTimeout(function () {
                    $('.docErr').fadeOut('slow');
                }, 9000);
            }
        }
    }
    $(document).ready(function () {

        $(document).on('change', '.up', function () {
            var id = $(this).attr('id'); /* gets the filepath and filename from the input */
            var profilePicValue = $(this).val();
            var fileNameStart = profilePicValue.lastIndexOf('\\'); /* finds the end of the filepath */
            profilePicValue = profilePicValue.substr(fileNameStart + 1).substring(0, 20); /* isolates the filename */
            //var profilePicLabelText = $(".upl"); /* finds the label text */
            if (profilePicValue != '') {
                //console.log($(this).closest('.fileUpload').find('.upl').length);
                $(this).closest('.fileUpload').find('.upl').html(profilePicValue); /* changes the label text */
            }
        });

        $(".btn-new").on('click', function () {
            var rand = Math.floor(Math.random()*101);
            $("#uploader").append('<div class="row uploadDoc"><div class="col-sm-3"><div class="docErr">Please upload valid file</div><!--error--><div class="fileUpload btn btn-orange"> <img src="https://image.flaticon.com/icons/svg/136/136549.svg" class="icon"><span class="upl" id="upload">Upload document</span><input type="file" name="file_'+rand+'" class="upload up" id="up" onchange="readURL(this);" /></div></div><div class="col-sm-8"><input type="text" class="form-control" name="details_'+rand+'" placeholder="Details (optional)"></div><div class="col-sm-1"><a class="btn-check"><i class="fa fa-times"></i></a></div></div>');
        });

        $(document).on("click", "a.btn-check", function () {
            if ($(".uploadDoc").length > 1) {
                $(this).closest(".uploadDoc").remove();
            } else {
					swal("Warning!", 'You have to upload at least one document.', "warning");
            }
        });
    });
</script>
@endpush