@if ($actions->contains('edit'))
    @can('update', $model)
        <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route($resource.'.edit', $model) }}" data-toggle="tooltip" data-original-title="Edit">
            <i class="icon wb-wrench" aria-hidden="true"></i>
        </a>
    @endcan
@endif

@if ($actions->contains('view'))
    @can('view', $model)
        <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route($resource.'.show', $model) }}" data-toggle="tooltip" data-original-title="View">
            <i class="icon wb-eye" aria-hidden="true"></i>
        </a>
    @endcan
@endif

@if ($actions->contains('delete'))
    @can('delete', $model)
        <form style="display: inline-block;" action="{{ route($resource.'.destroy', $model) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button style="cursor: pointer" class="btn btn-sm btn-icon btn-flat btn-default" type="submit" data-toggle="tooltip" data-original-title="Delete">
                <i class="icon wb-close" aria-hidden="true"></i>
            </button>
        </form>
    @endcan
@endif

@if ($actions->contains('retire'))
    @can('retire', $model)
        <form style="display: inline-block;" action="{{ route($resource.'.retire', $model->id) }}" method="POST">
            {{ csrf_field() }}
            <button style="cursor: pointer" class="btn btn-sm btn-icon btn-flat btn-default" type="submit" data-toggle="tooltip" data-original-title="Retire">
                <i class="icon wb-star" aria-hidden="true"></i>
            </button>
        </form>
    @endcan
@endif

@if ($actions->contains('unretire'))
    @can('unretire', $model)
        <form style="display: inline-block;" action="{{ route('retired-'.$resource.'.unretire', $model) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button style="cursor: pointer" class="btn btn-sm btn-icon btn-flat btn-default" type="submit" data-toggle="tooltip" data-original-title="Unretire">
                <i class="icon wb-close" aria-hidden="true"></i>
            </button>
        </form>
    @endcan
@endif

@if ($actions->contains('archive'))
    @can('archive', $model)
        <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route($resource.'.store', $model) }}" data-toggle="tooltip" data-original-title="Archive">
            <i class="icon wb-star" aria-hidden="true"></i>
        </a>
    @endcan
@endif

@if ($actions->contains('activate'))  
    @can('activate', $model)
        <form style="display: inline-block;" action="{{ route('inactive-'.$resource.'.activate', $model->id) }}" method="POST">
            {{ csrf_field() }}
            <button style="cursor: pointer" class="btn btn-sm btn-icon btn-flat btn-default" type="submit" data-toggle="tooltip" data-original-title="Activate">
                <i class="icon wb-star" aria-hidden="true"></i>
            </button>
        </form>
    @endcan
@endif

@if ($actions->contains('deactivate'))
    @can('deactivate', $model)
        <form style="display: inline-block;" action="{{ route('active-'.$resource.'.deactivate', $model->id) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button style="cursor: pointer" class="btn btn-sm btn-icon btn-flat btn-default" type="submit" data-toggle="tooltip" data-original-title="Deactivate">
                <i class="icon wb-close" aria-hidden="true"></i>
            </button>
        </form>
    @endcan
@endif