<form action="{{ $url or Request::url() }}" class="form-inline" method="POST">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <button type='submit' class="{{ $class or 'btn btn-link' }}" value="{{ $value or 'delete' }}">{!! $text or 'delete' !!}</button>
</form>