<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsAdminDataTables;
use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Admin\Concerns\GuardsDeletions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMediaAssetRequest;
use App\Http\Requests\Admin\UpdateMediaAssetRequest;
use App\Models\MediaAsset;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    use BuildsAdminDataTables;
    use FlashesMessages;
    use GuardsDeletions;

    public function index()
    {
        return view('admin.media.index');
    }

    public function create()
    {
        return view('admin.media.create');
    }

    public function store(StoreMediaAssetRequest $request)
    {
        $file = $request->file('file');
        $disk = config('media.disk');
        $path = $file->storeAs($this->uploadDirectory(), $this->storedFilename($file), $disk);
        $dimensions = $this->imageDimensions($file);

        MediaAsset::create([
            ...$request->validatedWithFileMetadata(),
            'uploaded_by' => $request->user()->id,
            'disk' => $disk,
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'width' => $dimensions['width'],
            'height' => $dimensions['height'],
        ]);

        return $this->success(redirect()->route('admin.media.index'), 'Media uploaded successfully.');
    }

    public function edit(MediaAsset $media)
    {
        return view('admin.media.edit', [
            'media' => $media->load('uploadedBy:id,name'),
            'usage' => $media->usageBreakdown(),
        ]);
    }

    public function update(UpdateMediaAssetRequest $request, MediaAsset $media)
    {
        $attributes = $request->mediaAttributes();
        $metadata = array_merge(
            Arr::except($media->metadata ?? [], array_keys(config('media.metadata_fields', []))),
            $attributes['metadata'] ?? [],
        );

        $media->update([
            'alt_text' => $attributes['alt_text'],
            'metadata' => $metadata === [] ? null : $metadata,
        ]);

        return $this->success(redirect()->route('admin.media.index'), 'Media updated successfully.');
    }

    public function destroy(MediaAsset $media)
    {
        $blocked = $this->deletionBlockedMessage(
            'This media',
            collect($media->usageBreakdown())->pluck('count', 'label')->all(),
        );

        if ($blocked !== null) {
            return $this->error(back(), $blocked);
        }

        if ($media->is_stored_on_disk) {
            Storage::disk($media->disk)->delete($media->path);
        }

        $media->delete();

        return $this->success(redirect()->route('admin.media.index'), 'Media deleted successfully.');
    }

    public function data()
    {
        return $this->adminDataTable(
            MediaAsset::query()
                ->with('uploadedBy:id,name')
                ->select(['id', 'uploaded_by', 'disk', 'path', 'original_name', 'mime_type', 'size_bytes', 'width', 'height', 'alt_text', 'created_at'])
                ->withUsageCount()
        )
            ->addColumn('preview', fn (MediaAsset $media) => $this->renderAdminPartial('admin.media.partials.preview', compact('media')))
            ->addColumn('file', fn (MediaAsset $media) => $this->renderAdminPartial('admin.media.partials.file', compact('media')))
            ->editColumn('mime_type', fn (MediaAsset $media) => $this->renderStatusBadge($media->mime_type ?? 'Unknown', $media->is_image ? 'active' : 'muted'))
            ->editColumn('size_bytes', fn (MediaAsset $media) => $this->formatBytes($media->size_bytes))
            ->addColumn('dimensions', fn (MediaAsset $media) => $media->dimensions ?? 'N/A')
            ->addColumn('uploaded_by', fn (MediaAsset $media) => $media->uploadedBy?->name ?? 'System')
            ->addColumn('usage', fn (MediaAsset $media) => $this->renderStatusBadge(
                $media->isInUse() ? 'In use ('.$media->usageCount().')' : 'Unused',
                $media->isInUse() ? 'role' : 'muted',
            ))
            ->editColumn('created_at', fn (MediaAsset $media) => $this->formatAdminDate($media->created_at, 'd M Y'))
            ->addColumn('actions', fn (MediaAsset $media) => $this->renderAdminPartial('admin.media.partials.actions', compact('media')))
            ->rawColumns(['preview', 'file', 'mime_type', 'usage', 'actions'])
            ->toJson();
    }

    /**
     * Select2 source for the shared media picker.
     */
    public function options(Request $request)
    {
        $perPage = 20;
        $page = max(1, (int) $request->integer('page', 1));
        $term = trim((string) $request->query('q', ''));

        $query = MediaAsset::query()
            ->select(['id', 'disk', 'path', 'original_name', 'mime_type', 'alt_text', 'width', 'height'])
            ->when($term !== '', fn ($builder) => $builder->where(function ($builder) use ($term): void {
                $builder->where('original_name', 'like', '%'.$term.'%')
                    ->orWhere('alt_text', 'like', '%'.$term.'%')
                    ->orWhere('path', 'like', '%'.$term.'%');
            }))
            ->orderByDesc('id');

        $total = (clone $query)->count();
        $assets = $query->forPage($page, $perPage)->get();

        return response()->json([
            'results' => $assets->map(fn (MediaAsset $media): array => [
                'id' => $media->id,
                'text' => $media->original_name ?: basename($media->path),
                'url' => $media->url,
                'is_image' => $media->is_image,
                'dimensions' => $media->dimensions,
            ])->all(),
            'pagination' => ['more' => $page * $perPage < $total],
        ]);
    }

    private function uploadDirectory(): string
    {
        return trim(config('media.paths.library'), '/').'/'.now()->format('Y/m');
    }

    private function storedFilename(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'media';

        return $baseName.'-'.now()->format('Ymd').'-'.Str::lower(Str::random(6)).'.'.$extension;
    }

    /**
     * @return array{width: int|null, height: int|null}
     */
    private function imageDimensions(UploadedFile $file): array
    {
        if (! in_array($file->getMimeType(), config('media.images.mime_types'), true)) {
            return ['width' => null, 'height' => null];
        }

        $dimensions = @getimagesize($file->getRealPath());

        return [
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
        ];
    }
}
