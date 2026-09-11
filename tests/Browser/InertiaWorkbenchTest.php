<?php

use Orchestra\Testbench\TestCase;
use Pest\Browser\Browsable;

uses(TestCase::class, Browsable::class);

$frameworks = [
    'Vue' => [['/inertia-workbench', '/react-inertia-workbench']],
    'React' => [['/react-inertia-workbench', '/svelte-inertia-workbench']],
    'Svelte' => [['/svelte-inertia-workbench', '/inertia-workbench']],
];

$colorSchemes = [
    'light' => false,
    'dark' => true,
];

it('mounts the SSR adapter without replacing its initial DOM', function (array $paths, bool $darkMode): void {
    [$path] = $paths;
    $ssrEnabled = filter_var(getenv('INERTIA_TEST_SSR'), FILTER_VALIDATE_BOOL);
    $browser = visit(rtrim(getenv('INERTIA_DEMO_URL'), '/') . '/login');

    if ($darkMode) {
        $browser->inDarkMode();
    }

    $browser->page()->locator('button[type="submit"]')->click(['noWaitAfter' => true]);
    $browser->assertSee('Welcome to the Filament Demo!');

    $captureInitialMarkup = <<<'JS'
        window.inertiaInsertionObserver = new MutationObserver(() => {
            const root = document.querySelector('#filament-inertia');
            const heading = document.querySelector('#inertia-report-title');
            if (!root || !heading) return;
            window.initialInertiaRoot = root;
            window.initialInertiaHeading = heading;
            window.initialInertiaMarkup = root.innerHTML;
            window.inertiaInsertionObserver.disconnect();
        });
        window.inertiaInsertionObserver.observe(document, { childList: true, subtree: true });
        JS;
    $browser->page()->context()->addInitScript($captureInitialMarkup);
    $browser->script($captureInitialMarkup);

    $browser->page()->locator("a[href$=\"{$path}\"]")->click(['noWaitAfter' => true]);
    $browser
        ->assertSeeIn('#deferred-analytics', '137')
        ->assertSeeIn('#deferred-permissions', 'review, publish')
        ->assertScript('document.querySelector("#filament-inertia").dataset.serverRendered === "true"', $ssrEnabled)
        ->assertNoJavaScriptErrors()
        ->assertNoAccessibilityIssues();

    if ($ssrEnabled) {
        $browser
            ->assertScript('window.initialInertiaRoot === document.querySelector("#filament-inertia")')
            ->assertScript('window.initialInertiaHeading === document.querySelector("#inertia-report-title")')
            ->assertScript('typeof window.initialInertiaMarkup === "string" && window.initialInertiaMarkup.length > 0');
    }
})->with($frameworks)->with($colorSchemes);

it('uses external navigation for report sections and preserves only shell-local state', function (array $paths, bool $darkMode): void {
    [$path] = $paths;
    $spaEnabled = filter_var(getenv('INERTIA_DEMO_SPA') ?: 'true', FILTER_VALIDATE_BOOL);
    $browser = visit(rtrim(getenv('INERTIA_DEMO_URL'), '/') . '/login');

    if ($darkMode) {
        $browser->inDarkMode();
    }

    $browser->page()->locator('button[type="submit"]')->click(['noWaitAfter' => true]);
    $browser->assertSee('Welcome to the Filament Demo!');
    $browser->script('window.documentSentinel = document; window.shellSentinel = document.querySelector("#fi-main-sidebar")');
    $browser->page()->locator("a[href$=\"{$path}\"]")->click(['noWaitAfter' => true]);
    $browser->assertSeeIn('#deferred-analytics', '137');
    $browser->script('window.workbenchDocumentSentinel = document; window.inertiaRootSentinel = document.querySelector("#filament-inertia")');

    $browser->fill('#report-notes', 'Discuss renewal for account 24');
    $browser->page()->locator("a[href$=\"{$path}?section=activity\"]")->click(['noWaitAfter' => true]);
    $browser
        ->assertSee('Recent activity')
        ->assertSeeIn('#mounted-section', 'activity')
        ->assertSeeIn('#content-section', 'activity')
        ->assertValue('#report-notes', 'Follow up with the customer success team.')
        ->assertScript('document === window.workbenchDocumentSentinel', $spaEnabled);

    if ($spaEnabled) {
        $browser->assertScript('history.state !== null && Object.hasOwn(history.state, "alpine") && !Object.hasOwn(history.state, "page")');
    }

    $browser->fill('#report-notes', 'Survives shell refresh');
    $browser->script('window.activityRoot = document.querySelector("#filament-inertia")');
    $browser->page()->locator('#refresh-shell')->click(['noWaitAfter' => true]);
    $browser
        ->assertSeeIn('#shell-refresh-count', '1')
        ->assertValue('#report-notes', 'Survives shell refresh')
        ->assertScript('document.querySelector("#filament-inertia") === window.activityRoot')
        ->assertNoAccessibilityIssues()
        ->assertNoJavaScriptErrors();
})->with($frameworks)->with($colorSchemes);

it('exercises props, forms, remembered state, and navigation round trips', function (array $paths, bool $darkMode): void {
    [$path, $otherFrameworkPath] = $paths;
    $browser = visit(rtrim(getenv('INERTIA_DEMO_URL'), '/') . '/login');

    if ($darkMode) {
        $browser->inDarkMode();
    }

    $browser->page()->locator('button[type="submit"]')->click(['noWaitAfter' => true]);
    $browser->assertSee('Welcome to the Filament Demo!');
    $browser->page()->locator("a[href$=\"{$path}\"]")->click(['noWaitAfter' => true]);
    $browser
        ->assertSeeIn('#deferred-analytics', '137')
        ->assertSeeIn('#deferred-permissions', 'review, publish')
        ->assertSeeIn('#optional-summary', 'Optional summary not requested')
        ->assertSeeIn('#optional-audit', 'Nested audit not requested');

    $catalogToken = $browser->script('document.querySelector("#catalog-token").textContent.trim()');
    $requestToken = $browser->script('document.querySelector("#request-token").textContent.trim()');
    $browser->page()->locator('#load-optional')->click(['noWaitAfter' => true]);
    $browser
        ->assertSeeIn('#optional-summary', 'Optional: 24 active accounts')
        ->assertSeeIn('#optional-audit', 'Nested optional audit loaded')
        ->assertScript('document.querySelector("#catalog-token").textContent.trim()', $catalogToken);
    expect($browser->script('document.querySelector("#request-token").textContent.trim()'))->not->toBe($requestToken);

    $browser->page()->locator('#refresh-catalog')->click(['noWaitAfter' => true]);
    $browser->assertScript('document.querySelector("#catalog-token").textContent.trim() !== ' . json_encode($catalogToken));
    $catalogToken = $browser->script('document.querySelector("#catalog-token").textContent.trim()');

    $browser->page()->locator('#merge-contacts')->click(['noWaitAfter' => true]);
    $browser->assertScript('Array.from(document.querySelectorAll("#merged-contacts li"), item => item.textContent.trim().replace(/\\s+/g, " "))', ['11: Aster', '24: Birch revised', '37: Cedar']);
    $browser->page()->locator('#reset-contacts')->click(['noWaitAfter' => true]);
    $browser->assertScript('Array.from(document.querySelectorAll("#merged-contacts li"), item => item.textContent.trim().replace(/\\s+/g, " "))', ['11: Aster', '24: Birch']);

    $browser->fill('#draft-title', 'No');
    $browser->page()->locator('#save-draft')->click(['noWaitAfter' => true]);
    $browser->assertPresent('#draft-error')->assertValue('#draft-title', 'No');
    $browser->fill('#draft-title', 'Quarterly review — café 42');
    $browser->page()->locator('#save-draft')->click(['noWaitAfter' => true]);
    $browser
        ->assertSeeIn('#saved-draft', 'Quarterly review — café 42')
        ->assertNotPresent('#draft-error')
        ->assertSeeIn('#draft-status', 'Ready')
        ->assertSeeIn('#mounted-section', 'activity')
        ->assertSeeIn('#content-section', 'activity');

    expect($browser->script('document.querySelector("#catalog-token").textContent.trim()'))->not->toBe($catalogToken);

    $browser->fill('#draft-title', 'Unsaved remembered text');
    $browser->page()->locator("a[href$=\"{$path}?section=overview\"]")->click(['noWaitAfter' => true]);
    $browser->assertSee('Quarterly overview')->back()->assertSee('Recent activity')->assertValue('#draft-title', 'Unsaved remembered text');

    $browser->fill('#component-title', 'No');
    $browser->page()->locator('#save-component')->click(['noWaitAfter' => true]);
    $browser->assertPresent('#component-error');
    $browser->fill('#component-title', 'Saved through Form component');
    $browser->page()->locator('#save-component')->click(['noWaitAfter' => true]);
    $browser
        ->assertSeeIn('#saved-draft', 'Saved through Form component')
        ->assertSeeIn('#component-success', 'Form component saved')
        ->assertValue('#component-title', '')
        ->assertSeeIn('#mounted-section', 'activity')
        ->assertSeeIn('#content-section', 'activity');

    $browser->fill('#http-title', 'No');
    $browser->page()->locator('#save-http')->click(['noWaitAfter' => true]);
    $browser->assertPresent('#http-error');
    $browser->fill('#http-title', 'Standalone JSON response');
    $browser->page()->locator('#save-http')->click(['noWaitAfter' => true]);
    $browser
        ->assertSeeIn('#http-result', 'Standalone JSON response')
        ->assertNotPresent('#http-error')
        ->assertSeeIn('#saved-draft', 'Saved through Form component')
        ->assertNoAccessibilityIssues();

    $browser->page()->getByRole('link', ['name' => 'Back to dashboard', 'exact' => true])->click(['noWaitAfter' => true]);
    $browser->assertSee('Welcome to the Filament Demo!');
    $browser->page()->locator("a[href$=\"{$otherFrameworkPath}\"]")->click(['noWaitAfter' => true]);
    $browser->assertSeeIn('#deferred-analytics', '137')->back()->assertSee('Welcome to the Filament Demo!')->forward()->assertSeeIn('#deferred-permissions', 'review, publish')->assertNoJavaScriptErrors();
})->with($frameworks)->with($colorSchemes);
