<?php

namespace App\LiveDemo;

final class Selection
{
    /** @return array{theme: 'stock'|'sharp'|'soft'|'noir', compact: bool, expanded: bool} */
    public static function current(): array
    {
        $request = request();
        if ($request->attributes->has('live-demo.selection')) {
            return $request->attributes->get('live-demo.selection');
        }

        $selection = ['theme' => 'stock', 'compact' => false, 'expanded' => false];
        if (! $request->hasSession()) {
            return $selection;
        }

        $saved = $request->session()->get('live-demo.selection', []);
        $isFirstVisit = ! $request->session()->has('live-demo.selection');
        if (is_array($saved) && in_array($saved['theme'] ?? null, ['stock', 'sharp', 'soft', 'noir'], true)) {
            $selection = [
                'theme' => $saved['theme'],
                'compact' => ($saved['compact'] ?? false) === true,
                'expanded' => $request->session()->get('live-demo.open', false) === true,
            ];
        }

        if ($request->isMethod('GET')) {
            $theme = $request->query('theme');
            $compact = $request->query('compact');
            if (in_array($theme, ['stock', 'sharp', 'soft', 'noir'], true)) {
                $selection['theme'] = $theme;
                $selection['expanded'] = $selection['expanded'] || $isFirstVisit;
            }
            if (in_array($compact, ['0', '1'], true)) {
                $selection['compact'] = $compact === '1';
                $selection['expanded'] = $selection['expanded'] || $isFirstVisit;
            }
        }

        if ($selection['expanded']) {
            $request->session()->put('live-demo.open', true);
        }

        $request->session()->put('live-demo.selection', [
            'theme' => $selection['theme'],
            'compact' => $selection['compact'],
        ]);
        $request->attributes->set('live-demo.selection', $selection);

        return $selection;
    }
}
