<?php

declare(strict_types=1);

namespace TentaPress\AdminShell\Navigation;

use Illuminate\Support\Facades\Route;
use TentaPress\System\Plugin\PluginRegistry;

final readonly class MenuRepository
{
    public function __construct(
        private PluginRegistry $registry,
    ) {
    }

    /**
     * Builds menu items from enabled plugin manifests in bootstrap/cache/tp_plugins.php
     *
     * Manifest shape supported:
     * {
     *   "admin": {
     *     "menus": [
     *       { "label": "Pages", "route": "tp.pages.index", "capability": "manage_pages", "icon": "file-text", "position": 20 }
     *     ]
     *   }
     * }
     *
     * @return array<int,array>
     */
    public function all(): array
    {
        $enabled = $this->registry->readCache();

        $items = [];

        // Always include Dashboard as the first item.
        $items[] = [
            'label' => 'Dashboard',
            'route' => 'tp.dashboard',
            'url' => Route::has('tp.dashboard') ? route('tp.dashboard') : '/admin',
            'capability' => null,
            'icon' => 'home',
            'position' => 0,
        ];

        foreach ($enabled as $info) {
            $manifest = $info['manifest'] ?? null;

            if (! is_array($manifest)) {
                continue;
            }

            $admin = $manifest['admin'] ?? null;

            if (! is_array($admin)) {
                continue;
            }

            $menus = $admin['menus'] ?? null;

            if (! is_array($menus)) {
                continue;
            }

            foreach ($menus as $m) {
                if (! is_array($m)) {
                    continue;
                }

                $label = isset($m['label']) ? (string) $m['label'] : '';
                $routeName = isset($m['route']) ? (string) $m['route'] : '';

                if ($label === '' || $routeName === '') {
                    continue;
                }

                $items[] = [
                    'label' => $label,
                    'route' => $routeName,
                    'url' => Route::has($routeName) ? route($routeName) : '#',
                    'capability' => isset($m['capability']) ? (string) $m['capability'] : null,
                    'icon' => isset($m['icon']) ? (string) $m['icon'] : null,
                    'position' => isset($m['position']) ? (int) $m['position'] : 100,
                ];
            }
        }

        // Sort by position, then label.
        usort($items, static function (array $a, array $b): int {
            $pos = ($a['position'] <=> $b['position']);

            if ($pos !== 0) {
                return $pos;
            }

            return strcmp($a['label'], $b['label']);
        });

        // De-dupe by route (last write wins, but sorted anyway)
        $deduped = [];

        foreach ($items as $item) {
            $deduped[$item['route']] = $item;
        }

        return array_values($deduped);
    }
}
