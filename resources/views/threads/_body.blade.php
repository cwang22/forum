<div class="card" v-if="editing" v-cloak>
    <div class="card-header">
        <input type="text" class="form-control" v-model="form.title">
    </div>
    <div class="card-body">
        <div class="form-group">
            <wysiwyg v-model="form.body"></wysiwyg>
        </div>
    </div>
    <div class="card-footer d-flex">
            <button class="btn btn-sm btn-primary mr-4" @click="update">Update</button>
            <button class="btn btn-sm btn-default" @click="cancel">Cancel</button>

            @can('update', $thread)
            <form action="{{ $thread->path() }}" method="POST" class="d-inline-block ml-auto">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button class="btn btn-sm btn-danger">Delete Thread</button>
            </form>
            @endcan
    </div>
</div>
<div class="card" v-else>
    <div class="card-header">
                <a href="{{ route('profile', $thread->owner) }}">{{ $thread->owner->username }}</a> posted:
                <span v-text="title"></span>
        </div>
    <div class="card-body">
            <highlight :content="body"></highlight>
    </div>
    <div class="card-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-sm" @click="editing = true">Edit</button>
    </div>
</div>

