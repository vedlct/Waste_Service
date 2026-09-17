<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsAdminDataTables;
use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMediaAssetRequest;
use App\Models\MediaAsset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    use BuildsAdminDataTables;
    use FlashesMessages;

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

    public function data()
    {
        return $this->adminDataTable(
            MediaAsset::query()
                ->with('uploadedBy:id,name')
                ->select(['id', 'uploaded_by', 'disk', 'path', 'original_name', 'mime_type', 'size_bytes', 'width', 'height', 'alt_text', 'created_at'])
        )
            ->addColumn('preview', fn (MediaAsset $media) => $this->renderAdminPartial('admin.media.partials.preview', compact('media')))
            ->addColumn('file', fn (MediaAsset $media) => $this->renderAdminPartial('admin.media.partials.file', compact('media')))
            ->editColumn('mime_type', fn (MediaAsset $media) => $this->renderStatusBadge($media->mime_type ?? 'Unknown', $media->is_image ? 'active' : 'muted'))
            ->editColumn('size_bytes', fn (MediaAsset $media) => $this->formatBytes($media->size_bytes))
            ->addColumn('dimensions', fn (MediaAsset $media) => $media->dimensions ?? 'N/A')
            ->addColumn('uploaded_by', fn (MediaAsset $media) => $media->uploadedBy?->name ?? 'System')
            ->editColumn('created_at', fn (MediaAsset $media) => $this->formatAdminDate($media->created_at, 'd M Y'))
            ->rawColumns(['preview', 'file', 'mime_type'])
            ->toJson();
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
