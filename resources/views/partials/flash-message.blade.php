@foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has($msg))
        <div id="flash-message" class="alert alert-{{$msg}} alert-dismissible fade show" role="alert">
            {{session($msg)}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endforeach
<script>
        setTimeout(function() {
            $('#flash-message').fadeOut('fast');
        }, 5000);
</script>
