<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $shop->name }} — Leave a Review</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/css/app.css') }}?v={{ filemtime(public_path('frontend/css/app.css')) }}">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .font-display {
            font-family: 'Outfit', 'Inter', sans-serif;
        }
        @keyframes popIn {
            0% {
                transform: scale(.92);
            }
            60% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
        .pop {
            animation: popIn .22s ease;
        }
        @keyframes stepIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .step-enter {
            animation: stepIn .3s ease both;
        }
    </style>
</head>

<body class="relative min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8 md:py-12 overflow-x-hidden">
    <div class="pointer-events-none fixed -top-24 -left-24 w-72 h-72 bg-blue-300/30 rounded-full blur-3xl"></div>
    <div class="pointer-events-none fixed -bottom-24 -right-24 w-72 h-72 bg-purple-300/30 rounded-full blur-3xl"></div>
    <div class="relative max-w-[560px] mx-auto px-4">
        <div class="flex justify-center">
            <div class="w-24 h-24 md:w-28 md:h-28 rounded-full ring-4 ring-white shadow-lg bg-white overflow-hidden flex items-center justify-center p-2.5">
                @if($shop->logo)
                <img src="{{ asset('storage/images/google-review/' . $shop->logo) }}" alt="{{ $shop->name }}"
                    class="max-w-full max-h-full object-contain">
                @else
                <span class="text-3xl md:text-4xl font-bold text-blue-600 font-display">{{ strtoupper(substr($shop->name, 0, 1)) }}</span>
                @endif
            </div>
        </div>

        <div class="text-center pt-4 pb-2 mb-4">
            <h4 class="text-xl md:text-2xl font-bold text-gray-900 font-display mb-0">{{ $shop->name }}</h4>
            @if($shop->address)
            <p class="text-sm text-gray-500 mt-1 flex items-center justify-center gap-1">
                <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.69 18.933l.003.001c.111.06.23.117.361.172l.082.033a.75.75 0 00.528 0l.082-.033a7.24 7.24 0 01.361-.172l.003-.001c.095-.047.187-.096.278-.148.34-.197.834-.504 1.388-.922.778-.593 1.676-1.397 2.515-2.372C16.757 13.906 18 11.68 18 9c0-4.418-3.582-8-8-8S2 4.582 2 9c0 2.68 1.243 4.906 2.709 6.491a13.87 13.87 0 002.515 2.372c.554.418 1.048.725 1.388.922.091.052.183.1.278.148zM10 11.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" clip-rule="evenodd" />
                </svg>
                {{ $shop->address }}
            </p>
            @endif
            <div class="flex items-center justify-center gap-0.5 mt-2 text-amber-400">
                @for ($i = 0; $i < 5; $i++)
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.538 1.118l-3.367-2.447a1 1 0 00-1.175 0l-3.367 2.447c-.783.57-1.838-.197-1.538-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z" /></svg>
                    @endfor
            </div>
            <p class="text-sm text-gray-500 mt-2 mb-0">We'd love to hear about your visit — it only takes a minute.</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>

            <div class="p-5 md:p-7">

                @if ($steps->isEmpty())
                    <div class="text-center py-10">
                        <p class="text-gray-500 text-sm mb-0">This review form isn't set up yet — please check back soon.</p>
                    </div>
                @else

                {{-- Question flow, rendered by JS from the STEPS config below --}}
                <div id="questionWrapper">
                    <div class="flex justify-between items-center mb-5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-gray-100 text-xs font-semibold text-gray-600 tracking-wide" id="stepCounter"></span>
                        <div class="bg-gray-100 rounded-full h-2 w-[55%] overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-300" id="progressBar" style="width:0%"></div>
                        </div>
                    </div>

                    <div id="questionArea"></div>

                    <div class="mt-6 flex justify-between items-center">
                        <button type="button" id="backBtn" class="hidden inline-flex items-center gap-1 text-sm font-medium text-gray-500 hover:text-gray-700 transition">
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd" />
                            </svg>
                            Back
                        </button>
                        <button type="button" id="nextBtn" class="ml-auto inline-flex items-center gap-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-600/20 disabled:opacity-40 disabled:shadow-none disabled:cursor-not-allowed hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition">
                            Next
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- AI draft, always editable before posting --}}
                <div id="draftStep" class="hidden">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="shrink-0 w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2l1.8 5.2L19 9l-5.2 1.8L12 16l-1.8-5.2L5 9l5.2-1.8L12 2z" />
                                <path d="M19 14l.8 2.2L22 17l-2.2.8L19 20l-.8-2.2L16 17l2.2-.8L19 14z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-base md:text-lg font-bold text-gray-900 font-display leading-snug mb-0">Here's a draft for you</p>
                            <p class="text-xs text-gray-500 mt-0.5 mb-0">Edit it so it sounds like you, then post it on Google.</p>
                        </div>
                    </div>

                    <div id="draftLoading" class="hidden text-center py-8">
                        <div class="inline-block w-7 h-7 border-[3px] border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                        <p class="text-sm text-gray-500 mt-3 mb-0">Writing your draft...</p>
                    </div>

                    <textarea id="draftText" class="hidden w-full border-2 border-gray-200 rounded-2xl p-4 text-sm leading-relaxed text-gray-800 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition" rows="6"></textarea>

                    <div id="draftError" class="hidden mt-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl p-3"></div>

                    <div class="hidden flex flex-col sm:flex-row gap-2.5 mt-4" id="draftActions">
                        <button type="button" id="regenBtn" class="inline-flex items-center justify-center gap-1.5 px-4 py-3 rounded-xl border-2 border-gray-200 text-sm font-semibold text-gray-700 hover:border-gray-300 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" />
                            </svg>
                            Regenerate
                        </button>
                        <button type="button" id="postBtn" class="flex-1 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white px-4 py-3 rounded-xl text-sm font-bold shadow-lg shadow-green-600/20 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition">
                            <svg width="18" height="18" viewBox="0 0 48 48" class="shrink-0">
                                <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12 c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24 c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z" />
                                <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039 l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z" />
                                <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36 c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z" />
                                <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571 c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z" />
                            </svg>
                            Copy &amp; Post on Google
                        </button>
                    </div>
                </div>

                @endif

            </div>
        </div>
        <p class="text-center text-gray-400 text-xs mt-4 flex items-center justify-center gap-1">
            <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
            </svg>
            Your review is written by you and posted under your own Google account.
        </p>
    </div>
    @if ($steps->isNotEmpty())
    <script>
        (function() {
            const googleUrl = @json($shop->google_review_url);
            const draftUrl = @json(route('review.draft', $shop->slug));
            const csrf = @json(csrf_token());
            const STEPS = @json($steps);
            const ICON_CLS = 'w-5 h-5 text-blue-600';
            const STEP_ICONS = {
                purchase_for: '<svg class="' + ICON_CLS + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8h12l-1 12H7L6 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>',
                items: '<svg class="' + ICON_CLS + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7l9-4 9 4-9 4-9-4z"/><path d="M3 7v10l9 4 9-4V7"/><path d="M12 11v10"/></svg>',
                brands: '<svg class="' + ICON_CLS + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12.5 3h6a1 1 0 0 1 1 1v6a1 1 0 0 1-.3.7l-9 9a1 1 0 0 1-1.4 0l-6.5-6.5a1 1 0 0 1 0-1.4l9-9a1 1 0 0 1 .7-.3z"/><circle cx="16.5" cy="7.5" r="1.3"/></svg>',
                instore_experience: '<svg class="' + ICON_CLS + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M8.5 10.2h.01M15.5 10.2h.01"/><path d="M8 14.5c1.2 1.3 2.6 2 4 2s2.8-.7 4-2"/></svg>',
                why_choose: '<svg class="' + ICON_CLS + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20.3s-7.5-4.6-9.8-9A5.3 5.3 0 0 1 12 6.4 5.3 5.3 0 0 1 21.8 11.3c-2.3 4.4-9.8 9-9.8 9z"/></svg>',
                recommend: '<svg class="' + ICON_CLS + '" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.538 1.118l-3.367-2.447a1 1 0 00-1.175 0l-3.367 2.447c-.783.57-1.838-.197-1.538-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/></svg>',
                extra: '<svg class="' + ICON_CLS + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 3.5l4 4L7 21l-4.5 1 1-4.5 13-14z"/><path d="M14.5 5.5l4 4"/></svg>',
            };
            const DEFAULT_STEP_ICON = '<svg class="' + ICON_CLS + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3h6l3 4v12a1 1 0 01-1 1H7a1 1 0 01-1-1V7l3-4z"/><path d="M9 11h6M9 15h6"/></svg>';

            const CHECK_PATH = 'M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 111.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z';

            const CHOICE_BASE = ['choice-btn', 'inline-flex', 'items-center', 'gap-1.5', 'px-4', 'py-2.5', 'rounded-xl', 'border-2', 'text-sm', 'font-semibold', 'transition-all', 'duration-200'];
            const ACTIVE_CLASSES = ['border-transparent', 'bg-gradient-to-r', 'from-blue-600', 'to-indigo-600', 'text-white', 'shadow-md'];
            const INACTIVE_CLASSES = ['border-gray-200', 'bg-white', 'text-gray-700', 'hover:border-gray-300'];

            function esc(s) {
                return String(s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');
            }

            const answers = {};
            let current = 0;

            const questionWrapper = document.getElementById('questionWrapper');
            const draftStep = document.getElementById('draftStep');
            const questionArea = document.getElementById('questionArea');
            const stepCounter = document.getElementById('stepCounter');
            const progressBar = document.getElementById('progressBar');
            const backBtn = document.getElementById('backBtn');
            const nextBtn = document.getElementById('nextBtn');

            function isStepValid(step) {
                if (step.type === 'text') {
                    if (!(step.min > 0)) return true;
                    const val = answers[step.id];
                    return !!(val && String(val).trim().length);
                }
                if (step.type === 'single') return (step.min || 0) === 0 || !!answers[step.id];
                const val = answers[step.id] || [];
                return val.length >= (step.min || 0);
            }

            function bounce(el) {
                if (!el) return;
                el.classList.remove('pop');
                void el.offsetWidth;
                el.classList.add('pop');
            }

            function setChoiceActive(btn, isActive) {
                btn.classList.remove.apply(btn.classList, isActive ? INACTIVE_CLASSES : ACTIVE_CLASSES);
                btn.classList.add.apply(btn.classList, isActive ? ACTIVE_CLASSES : INACTIVE_CLASSES);
                const icon = btn.querySelector('.check-ico');
                if (icon) icon.classList.toggle('hidden', !isActive);
            }

            function setupVoiceInput() {
                const micBtn = document.getElementById('micBtn');
                const textInput = document.getElementById('textInput');
                if (!micBtn || !textInput) return;

                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                if (!SpeechRecognition) {
                    micBtn.style.display = 'none';
                    return;
                }

                const recognition = new SpeechRecognition();
                recognition.lang = 'en-IN';
                recognition.interimResults = false;
                let listening = false;

                micBtn.addEventListener('click', function() {
                    if (listening) {
                        recognition.stop();
                        return;
                    }
                    recognition.start();
                    listening = true;
                    micBtn.classList.add('text-red-600');
                });

                recognition.addEventListener('result', function(e) {
                    const transcript = Array.from(e.results).map(function(r) {
                        return r[0].transcript;
                    }).join(' ');
                    textInput.value = (textInput.value ? textInput.value + ' ' : '') + transcript;
                });

                recognition.addEventListener('end', function() {
                    listening = false;
                    micBtn.classList.remove('text-red-600');
                });
            }

            function enforceMax(step, checkboxes) {
                if (!step.max) return;
                const count = (answers[step.id] || []).length;
                checkboxes.forEach(function(cb) {
                    cb.disabled = !cb.checked && count >= step.max;
                });
            }

            function attachStepHandlers(step) {
                if (step.type === 'single') {
                    const buttons = questionArea.querySelectorAll('.choice-btn');
                    buttons.forEach(function(btn) {
                        btn.addEventListener('click', function() {
                            buttons.forEach(function(b) {
                                setChoiceActive(b, false);
                            });
                            setChoiceActive(btn, true);
                            answers[step.id] = btn.dataset.value;
                            nextBtn.disabled = !isStepValid(step);
                            bounce(btn);
                        });
                    });
                } else if (step.type === 'multiple') {
                    const checkboxes = questionArea.querySelectorAll('input[type=checkbox]');
                    checkboxes.forEach(function(cb) {
                        cb.addEventListener('change', function() {
                            let val = answers[step.id] || [];
                            if (cb.checked) {
                                val.push(cb.value);
                            } else {
                                val = val.filter(function(v) {
                                    return v !== cb.value;
                                });
                            }
                            answers[step.id] = val;
                            enforceMax(step, checkboxes);
                            nextBtn.disabled = !isStepValid(step);

                            const chip = cb.nextElementSibling;
                            if (chip) {
                                const icon = chip.querySelector('.chip-check');
                                if (icon) icon.classList.toggle('hidden', !cb.checked);
                                if (cb.checked) bounce(chip);
                            }
                        });
                    });
                    enforceMax(step, checkboxes);
                } else if (step.type === 'text') {
                    setupVoiceInput();
                }

                nextBtn.disabled = !isStepValid(step);
            }

            function renderStep(i) {
                const step = STEPS[i];

                let html = '<div class="flex items-start gap-3 mb-4">' +
                    '<div class="shrink-0 w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">' + (STEP_ICONS[step.id] || DEFAULT_STEP_ICON) + '</div>' +
                    '<div class="min-w-0">' +
                    '<p class="text-base md:text-lg font-bold text-gray-900 font-display leading-snug mb-0">' + esc(step.question) + '</p>';
                if (step.hint) html += '<p class="text-xs text-gray-500 mt-0.5 mb-0">' + esc(step.hint) + '</p>';
                html += '</div></div>';

                if (step.type === 'text') {
                    const existing = answers[step.id] || '';
                    html += '<div class="flex justify-end mb-2">' +
                        '<button type="button" id="micBtn" class="text-gray-500 hover:text-gray-700 p-2 rounded-xl hover:bg-gray-100 transition" title="Speak instead of typing">🎤</button></div>' +
                        '<textarea id="textInput" class="w-full border-2 border-gray-200 rounded-2xl p-4 text-sm leading-relaxed focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition" rows="2" placeholder="' +
                        esc(step.placeholder || '') + '">' + esc(existing) + '</textarea>';
                } else {
                    const picked = answers[step.id] || (step.type === 'multiple' ? [] : null);
                    html += '<div class="flex flex-wrap' + (step.type === 'multiple' ? ' -m-1' : ' gap-2') + '">';
                    (step.options || []).forEach(function(opt) {
                        if (step.type === 'single') {
                            const isActive = picked === opt;
                            const cls = CHOICE_BASE.concat(isActive ? ACTIVE_CLASSES : INACTIVE_CLASSES).join(' ');
                            html += '<button type="button" class="' + cls + '" data-value="' + esc(opt) + '">' +
                                '<svg class="check-ico w-4 h-4' + (isActive ? '' : ' hidden') + '" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="' + CHECK_PATH + '" clip-rule="evenodd"/></svg>' +
                                '<span>' + esc(opt) + '</span>' +
                                '</button>';
                        } else {
                            const isChecked = picked.indexOf(opt) !== -1;
                            html += '<label class="cursor-pointer m-1">' +
                                '<input type="checkbox" value="' + esc(opt) + '"' + (isChecked ? ' checked' : '') + ' class="peer hidden">' +
                                '<span class="chip inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full border-2 border-gray-200 bg-white text-sm font-medium text-gray-700 ' +
                                'peer-checked:border-transparent peer-checked:bg-gradient-to-r peer-checked:from-blue-600 peer-checked:to-indigo-600 peer-checked:text-white peer-checked:shadow-md ' +
                                'peer-disabled:opacity-40 peer-disabled:cursor-not-allowed hover:border-gray-300 transition-all duration-200">' +
                                '<svg class="chip-check w-3.5 h-3.5' + (isChecked ? '' : ' hidden') + '" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="' + CHECK_PATH + '" clip-rule="evenodd"/></svg>' +
                                '<span>' + esc(opt) + '</span>' +
                                '</span>' +
                                '</label>';
                        }
                    });
                    html += '</div>';
                }

                questionArea.innerHTML = html;
                questionArea.classList.remove('step-enter');
                void questionArea.offsetWidth;
                questionArea.classList.add('step-enter');
                attachStepHandlers(step);
                updateChrome();
            }

            function updateChrome() {
                stepCounter.textContent = 'Step ' + (current + 1) + ' of ' + STEPS.length;
                progressBar.style.width = Math.round(((current + 1) / STEPS.length) * 100) + '%';
                backBtn.classList.toggle('hidden', current === 0);
            }

            backBtn.addEventListener('click', function() {
                if (current > 0) {
                    current--;
                    renderStep(current);
                }
            });

            nextBtn.addEventListener('click', function() {
                const step = STEPS[current];

                if (step.type === 'text') {
                    const textInput = document.getElementById('textInput');
                    answers[step.id] = textInput.value.trim() || null;
                }

                if (!isStepValid(step)) return;

                if (current === STEPS.length - 1) {
                    generateDraft();
                    return;
                }

                current++;
                renderStep(current);
            });

            function generateDraft() {
                questionWrapper.classList.add('hidden');
                draftStep.classList.remove('hidden');
                draftStep.classList.add('step-enter');

                const loading = document.getElementById('draftLoading');
                const textarea = document.getElementById('draftText');
                const errorBox = document.getElementById('draftError');
                const actions = document.getElementById('draftActions');

                loading.classList.remove('hidden');
                textarea.classList.add('hidden');
                actions.classList.add('hidden');
                errorBox.classList.add('hidden');

                fetch(draftUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(answers),
                    })
                    .then(async function(res) {
                        const body = await res.json();
                        if (!res.ok) throw new Error(body.message || 'Could not generate a draft.');
                        return body;
                    })
                    .then(function(body) {
                        textarea.value = body.draft;
                        loading.classList.add('hidden');
                        textarea.classList.remove('hidden');
                        actions.classList.remove('hidden');
                    })
                    .catch(function(err) {
                        loading.classList.add('hidden');
                        errorBox.textContent = err.message;
                        errorBox.classList.remove('hidden');
                    });
            }

            document.getElementById('regenBtn').addEventListener('click', generateDraft);

            document.getElementById('postBtn').addEventListener('click', function() {
                const text = document.getElementById('draftText').value;
                const btn = this;

                if (navigator.clipboard) {
                    navigator.clipboard.writeText(text).catch(function() {});
                }

                btn.disabled = true;
                btn.innerHTML = '✓ Copied! Opening Google...';

                setTimeout(function() {
                    window.location.href = googleUrl;
                }, 1200);
            });

            renderStep(current);
        })();
    </script>
    @endif
</body>

</html>