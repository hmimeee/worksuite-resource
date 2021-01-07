@extends('layouts.member-app')

@section('page-title')
<div class="row bg-title">
	<div class="col-lg-8 col-md-5 col-sm-6 col-xs-12">
		<h4 class="page-title"><a class="btn btn-sm btn-inverse" href="{{ route('member.resources.index') }}"><i class="fa fa-arrow-left"></i> Back to List</a> <i class="{{ $pageIcon ?? '' }}"></i> {{ $pageTitle }}</h4>
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
    padding: 35px 16px 25px;
    margin-right: 17px;
}
.tile:hover{
  box-shadow: 0px 10px 10px -6px rgba(0, 0, 0, 0.12);
}
.tile i{
    color: #ff4400;
    height: 55px;
    margin-bottom: 20px;
    font-size: 55px;
    display: block;
    line-height: 54px;
    cursor: pointer;
}
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="white-box">
        </div>
    </div>

    <div class="col-md-12">
        <div class="white-box">
            <div class="stage">
                <div class="folder-wrap">
                    @forelse($resources as $resource)
                    <div class="tile form">
                        <i class="fa {{ $icons[$resource->ext] ?? 'fa-file' }}"></i>
                        <h3>{{ $resource->name }}</h3>
                        <p>{{ $resource->details ?? '...' }}</p>
                        <p>
                            @if(auth()->user()->hasRole('admin'))
                            <a id="restoreFile" href="{{ route('member.resources.restore', $resource->id) }}">Restore</a> | <a id="destroyFile" href="{{ route('member.resources.destroy', $resource->id) }}">Destroy</a> 
                            @endif
                        </p>
                    </div>
                    @empty
                    <div style="font-size: 20px;">
                        <img src="{{ asset('empty-box.gif') }}" height="150px" alt=""/>
                        The trash is empty!
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
    $('body #destroyFile').click(function(e) {
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

    $('body #restoreFile').click(function(e) {
        e.preventDefault();
        $.easyAjax({
            url: $(this).attr('href'),
            type: "POST",
            data: {'_token': "{{ csrf_token() }}", '_method': "PUT"},
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
</script>
@endpush