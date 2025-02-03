<div class="d-flex justify-content-center">
    <a href="{{ URL('edit-form-builder', $row->id) }}" title="{{ __('messages.common.edit') }}" 
       class="btn px-2 text-primary fs-3 pe-0">
        <i class="fa-solid fa-edit"></i>
    </a>
    
    <a href="{{ URL('read-form-builder', $row->id) }}" title="{{ __('messages.common.show') }}" 
       class="btn px-2 text-info fs-3 pe-0">
        <i class="fa-solid fa-eye"></i>
    </a>
    <button type="button" onclick="confirmDelete({{ $row->id }})" title="{{ __('messages.common.delete') }}" class="btn px-2 text-danger fs-3 pe-0">
        <i class="fa-solid fa-trash"></i>
    </button>
</div>

<script>
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this form?")) {
            // Emit the delete event after confirmation
            Livewire.emit('delete', id);
        }
    }
</script>