<a type="button" class="btn btn-primary pt-3" href="{{ route('job.create') }}">
    {{ __('messages.common.add') }}
</a>
<br>
<div class="menu-item px-2">
        <a href="{{ route('candidates.export.excel') }}" type="button" class="btn btn-primary" data-turbo="false">
            {{ __('messages.common.export_excel') }}
        </a>
    </div>
    