@php
    $headerUser = auth()->user();
    $headerName = $headerUser->name;
    $headerInitials = collect(preg_split('/\s+/', trim($headerName)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('');
    $headerRole = \Illuminate\Support\Str::headline($headerUser->role);
    $hasNotificationTable = \Illuminate\Support\Facades\Schema::hasTable('notifications');
    $unreadNotifications = $hasNotificationTable
        ? $headerUser->unreadNotifications()->latest()->limit(5)->get()
        : collect();
    $unreadCount = $hasNotificationTable ? $headerUser->unreadNotifications()->count() : 0;
    $pendingReceiptCount = ($headerUser->isAdmin() || $headerUser->isSuperAdmin())
        ? \App\Models\Receipt::where('status', 'pending')->count()
        : 0;
    $notificationCount = $unreadCount + $pendingReceiptCount;
@endphp

<header class="phoenix-header">
    <div class="phoenix-brand">
        @if($hasSidebar ?? false)
            <button class="phoenix-icon-button nav-toggle phoenix-nav-toggle" type="button" onclick="toggleSidebar()" aria-label="Toggle navigation" aria-controls="sidebar" aria-expanded="false" title="Toggle navigation">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        @endif
        <a class="phoenix-brand-link" href="{{ route('dashboard') }}" aria-label="Phoenix Open University home">
            <img class="phoenix-logo" src="{{ asset('images/phoenix-open-university.jpeg') }}" alt="">
            <span class="phoenix-brand-copy">
                <strong>Phoenix Open University</strong>
                <small>Management System</small>
            </span>
        </a>
    </div>

    <div class="phoenix-search-wrap">
        <form class="phoenix-search" role="search" autocomplete="off">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m16 16 4 4"/></svg>
            <input id="phoenix-global-search" type="search" placeholder="Search students, courses, programmes..." aria-label="Search system records" aria-controls="phoenix-search-results" aria-expanded="false">
            <kbd><span>Ctrl</span><span>K</span></kbd>
        </form>
        <div id="phoenix-search-results" class="phoenix-search-results" role="listbox" aria-label="Search results" hidden></div>
        <p id="phoenix-search-status" class="phoenix-visually-hidden" aria-live="polite"></p>
    </div>

    <div class="phoenix-header-actions">
        <div class="phoenix-popover-wrap">
            <button class="phoenix-icon-button" type="button" data-header-toggle="notifications" aria-label="Notifications" aria-expanded="false" title="Notifications">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg>
                @if($notificationCount > 0)
                    <span class="phoenix-notification-badge">{{ $notificationCount > 9 ? '9+' : $notificationCount }}</span>
                @endif
            </button>
            <section class="phoenix-popover phoenix-notifications" data-header-popover="notifications" aria-label="Notifications" hidden>
                <h2>Notifications</h2>
                @if($pendingReceiptCount > 0)
                    <a class="phoenix-notification-item" href="{{ route('receipts.pending') }}">
                        <span class="phoenix-notification-dot"></span>
                        <span><strong>{{ $pendingReceiptCount }} receipt{{ $pendingReceiptCount === 1 ? '' : 's' }} awaiting review</strong><small>Receipt management</small></span>
                    </a>
                @endif
                @forelse($unreadNotifications as $notification)
                    <div class="phoenix-notification-item">
                        <span class="phoenix-notification-dot"></span>
                        <span><strong>{{ data_get($notification->data, 'title', 'New notification') }}</strong><small>{{ data_get($notification->data, 'message', 'Unread notification') }}</small></span>
                    </div>
                @empty
                    @if($pendingReceiptCount === 0)
                        <p class="phoenix-empty-notifications">You’re all caught up.</p>
                    @endif
                @endforelse
            </section>
        </div>

        <button class="phoenix-icon-button" type="button" data-help-open aria-label="Help" title="Help">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M9.6 9a2.5 2.5 0 1 1 4.2 1.8c-1.1 1-1.8 1.3-1.8 3M12 17.5h.01"/></svg>
        </button>

        <div class="phoenix-popover-wrap phoenix-profile-wrap">
            <button class="phoenix-profile-button" type="button" data-header-toggle="profile" aria-label="Open profile menu" aria-expanded="false">
                <span class="phoenix-avatar" aria-hidden="true">{{ $headerInitials }}</span>
                <span class="phoenix-profile-copy"><strong>{{ $headerName }}</strong><small>{{ $headerRole }}</small></span>
                <svg class="phoenix-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg>
            </button>
            <nav class="phoenix-popover phoenix-profile-menu" data-header-popover="profile" aria-label="Account menu" hidden>
                <a href="{{ route('profile.edit') }}">Profile &amp; account settings</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Sign out</button>
                </form>
            </nav>
        </div>
    </div>
</header>

<form id="logout-form" action="{{ route('logout') }}" method="POST" hidden>
    @csrf
</form>

<script>
    (() => {
        try {
            if (localStorage.getItem('phoenix-sidebar-collapsed-{{ $headerUser->role }}') === '1') {
                document.body.classList.add('sidebar-collapsed');
            }
        } catch (error) {}
    })();
</script>

<dialog class="phoenix-help-dialog" aria-labelledby="phoenix-help-title">
    <div class="phoenix-help-content">
        <button type="button" class="phoenix-icon-button phoenix-help-close" data-help-close aria-label="Close help dialog" title="Close help">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
        </button>
        <span class="phoenix-help-mark" aria-hidden="true">?</span>
        <h2 id="phoenix-help-title">System help</h2>
        <p>For help with your account or university records, contact your system administrator.</p>
        <button type="button" class="phoenix-help-done" data-help-close>Close</button>
    </div>
</dialog>

<style>
    .phoenix-header {
        position: sticky;
        top: 0;
        z-index: 1200;
        display: grid;
        grid-template-columns: minmax(240px, 1fr) minmax(260px, 520px) minmax(240px, 1fr);
        align-items: center;
        gap: 1.5rem;
        min-height: 76px;
        padding: 0.75rem clamp(1rem, 2.2vw, 2rem);
        background: rgba(255,255,255,0.97);
        border-bottom: 1px solid #e8e4f2;
        box-shadow: 0 3px 14px rgba(48, 36, 76, 0.06);
        color: #242033;
    }
    .phoenix-brand, .phoenix-brand-link, .phoenix-header-actions, .phoenix-profile-button { display: flex; align-items: center; }
    .phoenix-brand { gap: 0.75rem; min-width: 0; }
    .phoenix-brand-link { gap: 0.7rem; min-width: 0; text-decoration: none; color: inherit; }
    .phoenix-logo { width: 42px; height: 42px; flex: 0 0 42px; object-fit: contain; border-radius: 0.55rem; background: #f5f2fb; }
    .phoenix-brand-copy, .phoenix-profile-copy { display: grid; min-width: 0; gap: 0.15rem; }
    .phoenix-brand-copy strong { overflow: hidden; color: #29233a; font-size: 0.93rem; line-height: 1.15; text-overflow: ellipsis; white-space: nowrap; }
    .phoenix-brand-copy small { color: #777188; font-size: 0.75rem; }
    .phoenix-icon-button { position: relative; display: inline-grid; place-items: center; width: 40px; height: 40px; flex: 0 0 40px; padding: 0; color: #625a73; background: #fff; border: 1px solid #e6e1ef; border-radius: 0.65rem; cursor: pointer; transition: background .16s ease, border-color .16s ease, color .16s ease; }
    .phoenix-icon-button:hover { color: #5b2abf; background: #f6f2fc; border-color: #d7c9f0; }
    .phoenix-icon-button:focus-visible, .phoenix-profile-button:focus-visible, .phoenix-search input:focus-visible, .phoenix-help-done:focus-visible { outline: 3px solid rgba(109,40,217,.25); outline-offset: 2px; }
    .phoenix-icon-button svg, .phoenix-search svg, .phoenix-chevron { width: 20px; height: 20px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
    .phoenix-nav-toggle { display: none; }
    .phoenix-search-wrap { position: relative; width: 100%; }
    .phoenix-search { display: flex; align-items: center; gap: 0.7rem; min-height: 44px; padding: 0 0.65rem 0 0.85rem; background: #f8f6fc; border: 1px solid #e4deef; border-radius: 0.75rem; transition: background .16s ease, border-color .16s ease, box-shadow .16s ease; }
    .phoenix-search:focus-within { background: #fff; border-color: #a98add; box-shadow: 0 0 0 3px rgba(109,40,217,.1); }
    .phoenix-search > svg { flex: 0 0 19px; width: 19px; height: 19px; color: #776b8d; }
    .phoenix-search input { width: 100%; min-width: 0; height: 40px; padding: 0; color: #29233a; background: transparent; border: 0; outline: 0; font: inherit; font-size: 0.88rem; }
    .phoenix-search input::placeholder { color: #8b8499; }
    .phoenix-search input::-webkit-search-cancel-button { cursor: pointer; }
    .phoenix-search kbd { display: inline-flex; gap: 0.25rem; align-items: center; flex: 0 0 auto; padding: 0.2rem 0.4rem; color: #756d84; background: #fff; border: 1px solid #e2ddeb; border-radius: 0.35rem; font: inherit; font-size: 0.68rem; white-space: nowrap; }
    .phoenix-search-results, .phoenix-popover { position: absolute; z-index: 1300; background: #fff; border: 1px solid #e8e2f0; border-radius: 0.75rem; box-shadow: 0 16px 38px rgba(37,27,57,.16); }
    .phoenix-search-results { top: calc(100% + 0.5rem); left: 0; right: 0; max-height: min(420px, 65vh); overflow-y: auto; padding: 0.4rem; }
    .phoenix-search-result { display: grid; width: 100%; gap: 0.18rem; padding: 0.7rem 0.75rem; color: #282238; text-decoration: none; border-radius: 0.5rem; }
    .phoenix-search-result:hover, .phoenix-search-result:focus-visible, .phoenix-search-result[aria-selected="true"] { background: #f5f1fb; outline: none; }
    .phoenix-search-result-heading { display: flex; justify-content: space-between; gap: 0.75rem; align-items: center; }
    .phoenix-search-result-heading strong { overflow: hidden; font-size: 0.86rem; text-overflow: ellipsis; white-space: nowrap; }
    .phoenix-search-result-heading small, .phoenix-search-result-detail { color: #786f89; font-size: 0.73rem; }
    .phoenix-search-result-detail { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .phoenix-search-message { margin: 0; padding: 0.9rem; color: #746d81; font-size: 0.84rem; }
    .phoenix-visually-hidden { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; clip-path: inset(50%); }
    .phoenix-header-actions { justify-content: flex-end; gap: 0.55rem; min-width: 0; }
    .phoenix-popover-wrap { position: relative; }
    .phoenix-notification-badge { position: absolute; top: -4px; right: -4px; min-width: 17px; height: 17px; padding: 0 4px; display: grid; place-items: center; color: #fff; background: #d94762; border: 2px solid #fff; border-radius: 999px; font-size: 0.61rem; font-weight: 700; }
    .phoenix-popover { top: calc(100% + 0.65rem); right: 0; width: min(320px, calc(100vw - 2rem)); padding: 0.75rem; }
    .phoenix-popover h2 { margin: 0 0 0.55rem; color: #302741; font-size: 0.9rem; }
    .phoenix-notification-item { display: flex; align-items: flex-start; gap: 0.65rem; padding: 0.65rem 0.45rem; color: inherit; text-decoration: none; border-radius: 0.45rem; }
    .phoenix-notification-item:hover { background: #f7f4fb; }
    .phoenix-notification-item > span:last-child { display: grid; gap: 0.2rem; }
    .phoenix-notification-item strong { color: #352b46; font-size: 0.79rem; }
    .phoenix-notification-item small, .phoenix-empty-notifications { color: #7a7187; font-size: 0.72rem; }
    .phoenix-notification-dot { width: 8px; height: 8px; flex: 0 0 8px; margin-top: 0.25rem; background: #8857cf; border-radius: 50%; }
    .phoenix-empty-notifications { margin: 0; padding: 0.7rem 0.45rem; }
    .phoenix-profile-button { gap: 0.6rem; max-width: 230px; padding: 0.25rem 0.45rem; color: inherit; background: transparent; border: 0; border-radius: 0.65rem; text-align: left; cursor: pointer; }
    .phoenix-profile-button:hover { background: #f6f2fb; }
    .phoenix-avatar { display: grid; place-items: center; width: 38px; height: 38px; flex: 0 0 38px; color: #5b2abf; background: #eee7f9; border: 1px solid #e0d4f1; border-radius: 50%; font-size: 0.78rem; font-weight: 700; }
    .phoenix-profile-copy strong { max-width: 145px; overflow: hidden; color: #302a3d; font-size: 0.78rem; text-overflow: ellipsis; white-space: nowrap; }
    .phoenix-profile-copy small { color: #7e768b; font-size: 0.69rem; }
    .phoenix-chevron { width: 16px; height: 16px; color: #817990; }
    .phoenix-profile-menu { width: 235px; padding: 0.4rem; }
    .phoenix-profile-menu a, .phoenix-profile-menu button { display: block; width: 100%; padding: 0.7rem 0.65rem; color: #342d42; background: transparent; border: 0; border-radius: 0.45rem; font: inherit; font-size: 0.82rem; text-align: left; text-decoration: none; cursor: pointer; }
    .phoenix-profile-menu a:hover, .phoenix-profile-menu button:hover { background: #f5f1fb; }
    .phoenix-profile-menu form { border-top: 1px solid #eee9f4; margin-top: 0.25rem; padding-top: 0.25rem; }
    .phoenix-profile-menu form button { color: #a13950; }
    .phoenix-help-dialog { width: min(420px, calc(100% - 2rem)); margin: auto; padding: 0; color: #302741; background: #fff; border: 1px solid #e8e2f0; border-radius: 1rem; box-shadow: 0 24px 70px rgba(32,20,52,.24); }
    .phoenix-help-dialog::backdrop { background: rgba(31,24,43,.38); backdrop-filter: blur(2px); }
    .phoenix-help-content { position: relative; padding: 2rem; text-align: center; }
    .phoenix-help-close { position: absolute; top: 0.75rem; right: 0.75rem; }
    .phoenix-help-mark { display: grid; place-items: center; width: 42px; height: 42px; margin: 0 auto 0.8rem; color: #5b2abf; background: #f0eafa; border-radius: 50%; font-size: 1.25rem; font-weight: 700; }
    .phoenix-help-content h2 { margin: 0 0 0.55rem; font-size: 1.05rem; }
    .phoenix-help-content p { margin: 0; color: #746c81; font-size: 0.88rem; line-height: 1.55; }
    .phoenix-help-done { margin-top: 1.35rem; padding: 0.65rem 1.1rem; color: #fff; background: #6332bd; border: 0; border-radius: 0.55rem; font: inherit; font-size: 0.83rem; font-weight: 600; cursor: pointer; }
    .phoenix-help-done:hover { background: #5124a7; }
    @media (max-width: 1000px) {
        .phoenix-header { grid-template-columns: minmax(210px, 1fr) minmax(210px, 1.2fr) auto; gap: 0.8rem; }
        .phoenix-brand-copy strong { max-width: 210px; }
        .phoenix-profile-copy { display: none; }
        .phoenix-profile-button { padding: 0.15rem; }
        .phoenix-chevron { display: none; }
    }
    @media (max-width: 680px) {
        .phoenix-header { grid-template-columns: 1fr auto; gap: 0.65rem; min-height: 109px; padding: 0.55rem 0.75rem; }
        .phoenix-brand { gap: 0.45rem; }
        .phoenix-logo { width: 36px; height: 36px; flex-basis: 36px; }
        .phoenix-brand-copy strong { max-width: 185px; font-size: 0.82rem; }
        .phoenix-brand-copy small { font-size: 0.68rem; }
        .phoenix-search-wrap { grid-column: 1 / -1; grid-row: 2; }
        .phoenix-search { min-height: 40px; }
        .phoenix-search input { height: 36px; font-size: 0.82rem; }
        .phoenix-search kbd { font-size: 0.62rem; }
        .phoenix-header-actions { grid-column: 2; grid-row: 1; gap: 0.35rem; }
        .phoenix-header-actions > .phoenix-icon-button { width: 36px; height: 36px; flex-basis: 36px; }
        .phoenix-nav-toggle { display: inline-grid; width: 36px; height: 36px; flex-basis: 36px; }
        .phoenix-profile-button { display: flex; width: 36px; max-width: 36px; justify-content: center; padding: 0; }
        .phoenix-avatar { width: 32px; height: 32px; flex-basis: 32px; }
    }
    @media (prefers-reduced-motion: reduce) { .phoenix-header *, .phoenix-header *::before, .phoenix-header *::after { transition-duration: .01ms !important; scroll-behavior: auto !important; } }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('phoenix-global-search');
        const searchForm = document.querySelector('.phoenix-search');
        const searchResults = document.getElementById('phoenix-search-results');
        const searchStatus = document.getElementById('phoenix-search-status');
        const searchUrl = @json(route('global.search'));
        let searchTimer;
        let activeResult = -1;

        function closePopover(name) {
            document.querySelector(`[data-header-popover="${name}"]`)?.setAttribute('hidden', '');
            document.querySelector(`[data-header-toggle="${name}"]`)?.setAttribute('aria-expanded', 'false');
        }

        document.querySelectorAll('[data-header-toggle]').forEach((toggle) => {
            toggle.addEventListener('click', function (event) {
                event.stopPropagation();
                const name = toggle.dataset.headerToggle;
                const popover = document.querySelector(`[data-header-popover="${name}"]`);
                const willOpen = popover?.hasAttribute('hidden');
                document.querySelectorAll('[data-header-popover]').forEach((item) => item.setAttribute('hidden', ''));
                document.querySelectorAll('[data-header-toggle]').forEach((item) => item.setAttribute('aria-expanded', 'false'));
                if (willOpen) {
                    popover.removeAttribute('hidden');
                    toggle.setAttribute('aria-expanded', 'true');
                }
            });
        });

        document.addEventListener('click', function (event) {
            if (!event.target.closest('.phoenix-popover-wrap')) {
                closePopover('notifications');
                closePopover('profile');
            }
        });

        document.querySelectorAll('[data-help-open]').forEach((button) => {
            button.addEventListener('click', () => document.querySelector('.phoenix-help-dialog')?.showModal());
        });
        document.querySelectorAll('[data-help-close]').forEach((button) => {
            button.addEventListener('click', () => document.querySelector('.phoenix-help-dialog')?.close());
        });

        function showSearchMessage(message) {
            searchResults.replaceChildren();
            const item = document.createElement('p');
            item.className = 'phoenix-search-message';
            item.textContent = message;
            searchResults.append(item);
            searchResults.hidden = false;
            searchInput.setAttribute('aria-expanded', 'true');
            searchStatus.textContent = message;
        }

        function renderSearchResults(results) {
            searchResults.replaceChildren();
            activeResult = -1;

            if (!results.length) {
                showSearchMessage('No matching records found.');
                return;
            }

            results.forEach((result, index) => {
                const link = document.createElement('a');
                link.className = 'phoenix-search-result';
                link.href = result.url;
                link.setAttribute('role', 'option');
                link.dataset.resultIndex = String(index);

                const heading = document.createElement('span');
                heading.className = 'phoenix-search-result-heading';
                const title = document.createElement('strong');
                title.textContent = result.title;
                const type = document.createElement('small');
                type.textContent = result.type;
                heading.append(title, type);

                const detail = document.createElement('span');
                detail.className = 'phoenix-search-result-detail';
                detail.textContent = result.detail;
                link.append(heading, detail);
                searchResults.append(link);
            });

            searchResults.hidden = false;
            searchInput.setAttribute('aria-expanded', 'true');
            searchStatus.textContent = `${results.length} matching records.`;
        }

        async function searchRecords() {
            const query = searchInput.value.trim();
            if (query.length < 2) {
                searchResults.hidden = true;
                searchInput.setAttribute('aria-expanded', 'false');
                return;
            }

            showSearchMessage('Searching records…');
            try {
                const response = await fetch(`${searchUrl}?q=${encodeURIComponent(query)}`, {
                    headers: { Accept: 'application/json' },
                    credentials: 'same-origin',
                });
                if (!response.ok) throw new Error('Search request failed');
                const data = await response.json();
                if (searchInput.value.trim() === query) renderSearchResults(data.results || []);
            } catch (error) {
                showSearchMessage('Search is unavailable right now. Try again shortly.');
            }
        }

        searchInput?.addEventListener('input', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(searchRecords, 180);
        });
        searchInput?.addEventListener('keydown', function (event) {
            const links = [...searchResults.querySelectorAll('.phoenix-search-result')];
            if (event.key === 'ArrowDown' && links.length) {
                event.preventDefault();
                activeResult = (activeResult + 1) % links.length;
                links.forEach((link, index) => link.setAttribute('aria-selected', String(index === activeResult)));
            } else if (event.key === 'ArrowUp' && links.length) {
                event.preventDefault();
                activeResult = (activeResult - 1 + links.length) % links.length;
                links.forEach((link, index) => link.setAttribute('aria-selected', String(index === activeResult)));
            } else if (event.key === 'Enter' && activeResult >= 0 && links[activeResult]) {
                event.preventDefault();
                window.location.assign(links[activeResult].href);
            } else if (event.key === 'Escape') {
                searchResults.hidden = true;
                searchInput.setAttribute('aria-expanded', 'false');
                searchInput.blur();
            }
        });
        searchForm?.addEventListener('submit', function (event) {
            event.preventDefault();
            const firstResult = searchResults.querySelector('.phoenix-search-result');
            if (firstResult) window.location.assign(firstResult.href);
            else searchRecords();
        });
        document.addEventListener('keydown', function (event) {
            if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
                event.preventDefault();
                searchInput?.focus();
                searchInput?.select();
            }
            if (event.key === 'Escape') {
                closePopover('notifications');
                closePopover('profile');
            }
        });
        document.addEventListener('click', function (event) {
            if (!event.target.closest('.phoenix-search-wrap')) {
                searchResults.hidden = true;
                searchInput?.setAttribute('aria-expanded', 'false');
            }
        });
    });
</script>