# Media Storage

This document defines the upload/storage rules for the admin Media Library and any CMS modules that attach images or files.

## Disk And URLs

- Use Laravel's `public` disk by default via `config('media.disk')`.
- The public disk stores files under `storage/app/public` and serves them through `/storage/...`.
- Run `php8.4 artisan storage:link` in each environment before relying on uploaded public files.
- Store media database records in `media_assets`.
- Keep `media_assets.disk` as the configured disk name, usually `public`.
- Keep `media_assets.path` as a disk-relative path without a leading slash, for example `media/services/house-clearance/hero-20260917-a1b2c3.webp`.
- Seeded static frontend references may temporarily use existing `/images/...` paths, but new uploads should use disk-relative `media/...` paths.

## Folder Layout

Use these top-level folders from `config/media.php`:

- `media/library` for general Media Library uploads.
- `media/pages` for page/CMS hero and section images.
- `media/services` for service catalogue images.
- `media/settings` for logos, favicons, and settings-managed brand assets.
- `media/tmp` only for short-lived temporary uploads if needed later.

Module-specific uploads should nest by slug or record id when available:

- `media/services/{service-slug}/...`
- `media/pages/{page-slug}/...`
- `media/library/{yyyy}/{mm}/...`

## Naming

- Normalize the original filename to a lowercase slug.
- Append a short random suffix before the extension to avoid collisions.
- Prefer WebP only when an image-processing step is intentionally added. Until then, preserve the uploaded extension.
- Example: `sofa-clearance-20260917-x8f2ab.jpg`.

## Validation

Images:

- Allowed extensions: `jpg`, `jpeg`, `png`, `webp`.
- Allowed MIME types: `image/jpeg`, `image/png`, `image/webp`.
- Max size: `5120` KB.
- Max dimensions: `3000 x 3000`.

Files:

- Allowed extension: `pdf`.
- Allowed MIME type: `application/pdf`.
- Max size: `10240` KB.

Do not allow SVG uploads until sanitization is deliberately implemented.

## Metadata

For each stored file, populate:

- `uploaded_by`
- `disk`
- `path`
- `original_name`
- `mime_type`
- `size_bytes`
- `width` and `height` for images where available
- `alt_text` for images
- `metadata` for module-specific details only

## Deletion Rules

- Do not physically delete a file while it is assigned to pages, services, settings, or other content.
- The first Media Library implementation should block deletion of assigned files.
- If soft-delete or archive behavior is added later, document it here before changing behavior.

## Public API Shape

Frontend-facing APIs should expose media as an object, not just a raw id:

```json
{
  "id": 1,
  "url": "https://example.com/storage/media/services/house-clearance/hero.jpg",
  "alt": "House clearance team loading a van",
  "width": 1600,
  "height": 1000,
  "mime_type": "image/jpeg"
}
```
