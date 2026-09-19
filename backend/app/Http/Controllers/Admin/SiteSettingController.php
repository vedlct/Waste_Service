<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteSettingsRequest;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SiteSettingController extends Controller
{
    use FlashesMessages;

    public function index(?string $group = null)
    {
        $groups = $this->groups();

        if ($groups === []) {
            return view('admin.settings.index', [
                'groups' => [],
                'activeGroup' => null,
                'settings' => collect(),
            ]);
        }

        if ($group === null) {
            return redirect()->route('admin.settings.index', ['group' => array_key_first($groups)]);
        }

        if (! array_key_exists($group, $groups)) {
            throw new NotFoundHttpException('Unknown settings group.');
        }

        return view('admin.settings.index', [
            'groups' => $groups,
            'activeGroup' => $group,
            'settings' => SiteSetting::query()->group($group)->ordered()->get(),
        ]);
    }

    public function update(UpdateSiteSettingsRequest $request, string $group)
    {
        $settings = $request->groupSettings();

        if ($settings->isEmpty()) {
            throw new NotFoundHttpException('Unknown settings group.');
        }

        $input = $request->validated()['settings'] ?? [];

        DB::transaction(function () use ($settings, $input, $request): void {
            foreach ($settings as $setting) {
                if (! array_key_exists($setting->id, $input)) {
                    continue;
                }

                $setting->fill([
                    'value' => $setting->castInput($input[$setting->id]['value'] ?? null),
                    'is_public' => (bool) ($input[$setting->id]['is_public'] ?? false),
                    'updated_by' => $request->user()->id,
                ]);

                if ($setting->isDirty()) {
                    $setting->save();
                }
            }
        });

        return $this->success(
            redirect()->route('admin.settings.index', ['group' => $group]),
            'Settings updated successfully.',
        );
    }

    /**
     * Groups that exist in the database, ordered by the configured presentation order.
     *
     * @return array<string, array{label: string, icon: string, description: string|null}>
     */
    private function groups(): array
    {
        $configured = config('settings.groups', []);
        $stored = SiteSetting::query()->distinct()->orderBy('group')->pluck('group')->all();

        $ordered = array_values(array_unique(array_merge(
            array_values(array_intersect(array_keys($configured), $stored)),
            array_values(array_diff($stored, array_keys($configured))),
        )));

        $groups = [];

        foreach ($ordered as $group) {
            $groups[$group] = [
                'label' => $configured[$group]['label'] ?? str($group)->headline()->toString(),
                'icon' => $configured[$group]['icon'] ?? config('settings.fallback_icon', 'gear'),
                'description' => $configured[$group]['description'] ?? null,
            ];
        }

        return $groups;
    }
}
