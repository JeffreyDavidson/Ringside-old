<a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route($resource.'.edit', $item) }}" data-toggle="tooltip" data-original-title="Edit">
    <i class="icon wb-wrench" aria-hidden="true"></i>
</a>
<a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route($resource.'.show', $item) }}" data-toggle="tooltip" data-original-title="Show">
    <i class="icon wb-eye" aria-hidden="true"></i>
</a>
<form style="display: inline-block;" action="{{ route($resource.'.destroy', $item) }}" method="POST">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
    <button style="cursor: pointer" class="btn btn-sm btn-icon btn-flat btn-default" type="submit" data-toggle="tooltip" data-original-title="Delete">
        <i class="icon wb-close" aria-hidden="true"></i>
    </button>
</form>