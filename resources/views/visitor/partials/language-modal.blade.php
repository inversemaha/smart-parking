<!-- Language Switcher Modal -->
<div id="language-modal" class="modal modal-slide-over" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">{{ __('general.select_language') }}</h2>
                <button type="button" class="btn btn-icon" data-tw-dismiss="modal" aria-label="Close">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="space-y-4">
                    <div class="text-sm text-gray-600 mb-4">
                        {{ __('general.language_description') }}
                    </div>

                    <form method="POST" action="{{ route('visitor.language.switch', 'en') }}" class="inline-block w-full">
                        @csrf
                        <button type="submit" class="flex items-center w-full p-4 border-2 rounded-lg transition-all duration-200 {{ app()->getLocale() === 'en' ? 'border-primary bg-primary/10' : 'border-gray-200 hover:border-gray-300' }}">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-7 rounded bg-gradient-to-r from-blue-500 to-red-500 flex items-center justify-center">
                                    <span class="text-white font-bold text-xs">EN</span>
                                </div>
                                <div>
                                    <div class="font-semibold text-left">English</div>
                                    <div class="text-sm text-gray-500 text-left">Select English Language</div>
                                </div>
                            </div>
                            @if(app()->getLocale() === 'en')
                                <div class="ml-auto">
                                    <i data-lucide="check" class="size-5 text-primary"></i>
                                </div>
                            @endif
                        </button>
                    </form>

                    <form method="POST" action="{{ route('visitor.language.switch', 'bn') }}" class="inline-block w-full">
                        @csrf
                        <button type="submit" class="flex items-center w-full p-4 border-2 rounded-lg transition-all duration-200 {{ app()->getLocale() === 'bn' ? 'border-primary bg-primary/10' : 'border-gray-200 hover:border-gray-300' }}">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-7 rounded bg-gradient-to-r from-green-600 to-red-600 flex items-center justify-center">
                                    <span class="text-white font-bold text-xs">বাং</span>
                                </div>
                                <div>
                                    <div class="font-semibold text-left">বাংলা</div>
                                    <div class="text-sm text-gray-500 text-left">বাংলা ভাষা নির্বাচন করুন</div>
                                </div>
                            </div>
                            @if(app()->getLocale() === 'bn')
                                <div class="ml-auto">
                                    <i data-lucide="check" class="size-5 text-primary"></i>
                                </div>
                            @endif
                        </button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--secondary w-full" data-tw-dismiss="modal">
                    {{ __('general.close') }}
                </button>
            </div>
        </div>
    </div>
</div>
