<div class="panel panel-default" v-if="editing" v-cloak>
    <div class="panel-heading">
        <div class="level">
            <span class="flex">
                <input type="text" class="form-control" v-model="form.title">
            </span>
        </div>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <wysiwyg v-model="form.body"></wysiwyg>
        </div>
    </div>
    <div class="panel-footer">
        <div class="level">
            <button class="btn btn-xs btn-primary level-item" @click="update">Update</button>
            <button class="btn btn-xs btn-default level-item" @click="cancel">Cancel</button>

            @can('update', $thread)
                <form action="{{ $thread->path() }}" method="POST" class="ml-a">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button class="btn btn-xs btn-danger">Delete Thread</button>
                </form>
            @endcan
        </div>
    </div>
</div>
<div class="panel panel-default" v-else>
    <div class="panel-heading">
        <div class="level">
            <span class="flex">
                <a href="{{ route('profile', $thread->owner) }}">{{ $thread->owner->username }}</a> posted:
                <span v-text="title"></span>
            </span>
        </div>
    </div>
    <div class="panel-body">
            <highlight :content="body"></highlight>
    </div>
    <div class="panel-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-xs" @click="editing = true">Edit</button>
    </div>
</div>

