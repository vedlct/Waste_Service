<div class="d-flex align-items-center gap-3">
    <div class="rounded-circle d-grid text-white fw-black" style="width:42px;height:42px;background:#11224d;place-items:center;">
        {{ str($user->name)->substr(0, 1)->upper() }}
    </div>
    <div>
        <div class="fw-bold">{{ $user->name }}</div>
        <div class="small text-muted">#{{ $user->id }}</div>
    </div>
</div>
