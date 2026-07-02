<x-filament-panels::page>
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Детали ссылки</h3>
        <dl class="space-y-3">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Оригинальный URL</dt>
                <dd class="mt-1">
                    <a href="{{ $shortUrl->url }}" target="_blank" class="text-primary-600 hover:underline break-all">
                        {{ $shortUrl->url }}
                    </a>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Сокращение</dt>
                <dd class="mt-1">
                    <code class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-sm">
                        {{ $shortUrl->alias }}
                    </code>
                </dd>
            </div>
        </dl>
    </div>
</x-filament-panels::page>
