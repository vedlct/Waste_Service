<div class="d-inline-flex gap-2">
    <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.users.edit', $user) }}"><i class="bi bi-pencil-square"></i></a>
    <button
        class="btn btn-sm btn-outline-danger"
        type="button"
        data-delete-action="{{ route('admin.users.destroy', $user) }}"
        data-delete-title="Delete {{ $user->name }}?"
        data-delete-message="This will permanently remove the admin user account for {{ $user->email }}."
        @disabled($user->is(auth()->user()))
    >
        <i class="bi bi-trash3"></i>
    </button>
</div>
